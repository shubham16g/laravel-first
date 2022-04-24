<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllTag;
use App\Models\ConnectsAllTag;
use App\Models\Product;
use App\Models\SubVariation;
use Illuminate\Support\Facades\DB;

class SubVariationController extends Controller
{

    public function addProduct(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:100',
            'desc' => 'required|string|max:255',

            'sub_category' => 'required|string|max:100|exists:sub_categories,name',

            'variation' => 'required|string|max:50',

            'sub_variation' => 'nullable|array',
            'sub_variation.*.name' => 'required_with:sub_variation|string|max:50',
            'sub_variation.*.price' => 'required_with:sub_variation|numeric|min:0',
            'sub_variation.*.mrp' => 'required_with:sub_variation|gt:sub_variation.*.price|numeric|min:0',


            'price' => 'required_without:sub_variation.0.price|numeric|min:0',
            'mrp' => 'required_without:sub_variation.0.mrp|gt:price|numeric|min:0',

            'tags' => 'required|array',
            'tags.*' => 'required|max:50',

        ]);

        // todo check if sub_category group system and distinct system

        $allTags = [];

        $allTags[AllTag::firstOrCreate(['value' => $data['name'], 'type' => 'name'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $data['desc'], 'type' => 'desc'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $data['sub_category'], 'type' => 'sub_category'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $data['variation'], 'type' => 'variation'])->all_tag_id] = true;
        // also need the unit of variation
        foreach ($data['tags'] as $tag) {
            $allTags[AllTag::firstOrCreate(['value' => $tag, 'type' => 'tag'])->all_tag_id] = true;
        }

        $product = new Product();
        $currentProductID = $this->getNextId();
        $product->group_id = $currentProductID;
        $product->distinct_id = $currentProductID;

        $product->save();

        if (isset($data['sub_variation'])) {
            $minMrp = 0;
            $minPrice = 0;
            $maxMrp = 0;
            $maxPrice = 0;
            foreach ($data['sub_variation'] as $sub_variation) {
                $allTags[AllTag::firstOrCreate(['value' => $sub_variation['name'], 'type' => 'sub_variation'])->all_tag_id] = true;
                $subVariation = new SubVariation();
                $subVariation->product_id = $currentProductID;
                $subVariation->sub_variation = $sub_variation['name'];
                $subVariation->price = $sub_variation['price'];
                $subVariation->mrp = $sub_variation['mrp'];
                $subVariation->save();
                if ($subVariation['price'] < $minPrice || $minPrice == 0)
                    $minPrice = $subVariation['price'];
                if ($subVariation['mrp'] < $minMrp || $minMrp == 0)
                    $minMrp = $subVariation['mrp'];
                if ($subVariation['price'] > $maxPrice || $maxPrice == 0)
                    $maxPrice = $subVariation['price'];
                if ($subVariation['mrp'] > $maxMrp || $maxMrp == 0)
                    $maxMrp = $subVariation['mrp'];
            }
            $product->min_mrp = $minMrp;
            $product->min_price = $minPrice;
            $product->max_mrp = $maxMrp;
            $product->max_price = $maxPrice;
        } else {
            $product->min_mrp = $data['mrp'];
            $product->min_price = $data['price'];
            $product->max_mrp = $data['mrp'];
            $product->max_price = $data['price'];
        }
        $product->save();

        ConnectsAllTag::insert(array_map(function ($tag) use ($product) {
            return ['product_id' => $product->product_id, 'all_tag_id' => $tag];
        }, array_keys($allTags)));

        return response()->json(['message' => 'Product added successfully']);
    }

    public function deleteSubVariation(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:sub_variations,product_id',
            'name' => 'required|string|max:50|exists:sub_variations,sub_variation,product_id,' . $request->product_id,
        ]);

        $subVariation = SubVariation::where('product_id', $request->product_id)
        ->where('sub_variation', $request->name)->first();
        $subVariation->delete();

        $connect = ConnectsAllTag::where('product_id', $request->product_id)->leftJoin('all_tags', 'all_tags.all_tag_id', '=', 'connects_all_tags.all_tag_id')
        ->where('all_tags.type', 'sub_variation')->where('all_tags.value', $request->name)->first();
        $connect->delete();

        $product = Product::find($request->product_id);
        $product->min_mrp = $product->subVariations()->min('mrp');
        $product->min_price = $product->subVariations()->min('price');
        $product->max_mrp = $product->subVariations()->max('mrp');
        $product->max_price = $product->subVariations()->max('price');
        $product->save();

        return response()->json(['message' => 'Sub variation deleted successfully']);

    }

    public function addSubVariation(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,product_id',
            'name' => 'required|string|max:50|unique:sub_variations,sub_variation,null,null,product_id,' . $request->product_id,
            'price' => 'numeric|min:0',
            'mrp' => 'required_without:price|gt:price|numeric|min:0',
        ]);

        $subVariation = new SubVariation();
        $subVariation->product_id = $request->product_id;
        $subVariation->sub_variation = $request->name;
        $subVariation->price = $request->price;
        $subVariation->mrp = $request->mrp;
        $subVariation->save();

        $product = Product::find($request->product_id);
        if ($product->min_price > $request->price || $product->min_price == 0)
            $product->min_price = $request->price;
        if ($product->min_mrp > $request->mrp || $product->min_mrp == 0)
            $product->min_mrp = $request->mrp;
        if ($product->max_price < $request->price || $product->max_price == 0)
            $product->max_price = $request->price;
        if ($product->max_mrp < $request->mrp || $product->max_mrp == 0)
            $product->max_mrp = $request->mrp;

        $product->save();

        $allTagId = AllTag::firstOrCreate(['value' => $request->name, 'type' => 'sub_variation'])->all_tag_id;
        $connects = new ConnectsAllTag();
        $connects->product_id = $request->product_id;
        $connects->all_tag_id = $allTagId;
        $connects->save();

        return response()->json(['message' => 'Sub variation added successfully']);
    }

    public function updateSubVariationPrice(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:sub_variations,product_id',
            'name' => 'required|string|max:50|exists:sub_variations,sub_variation,product_id,' . $request->product_id,
            'price' => 'numeric|min:0',
            'mrp' => 'required_without:price|gt:price|numeric|min:0',
        ]);

        return $request->all();
        $subVariation = SubVariation::where('product_id', $request->product_id)
        ->where('sub_variation', $request->name)->first();
        if ($request->has('price'))
        $subVariation->price = $request->price;
        if ($request->has('mrp'))
        $subVariation->mrp = $request->mrp;
        $subVariation->save();

        $product = Product::find($request->product_id);
        if ($product->min_price > $request->price || $product->min_price == 0)
            $product->min_price = $request->price;
        if ($product->min_mrp > $request->mrp || $product->min_mrp == 0)
            $product->min_mrp = $request->mrp;
        if ($product->max_price < $request->price || $product->max_price == 0)
            $product->max_price = $request->price;
        if ($product->max_mrp < $request->mrp || $product->max_mrp == 0)
            $product->max_mrp = $request->mrp;
        $product->save();

        return response()->json(['message' => 'Sub variation price updated successfully']);
    }

    public function updateSubVariationName(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:sub_variations,product_id',
            'name' => 'required|string|max:50|exists:sub_variations,sub_variation,product_id,' . $request->product_id,
            'new_name' => 'required|string|max:50|unique:sub_variations,sub_variation,null,null,product_id,' . $request->product_id,
        ]);

        $allTagId = AllTag::firstOrCreate(['value' => $request->new_name, 'type' => 'sub_variation'])->all_tag_id;

        $subVariation = SubVariation::where('product_id', $request->product_id)
        ->where('sub_variation', $request->name)->first();

        $connect = ConnectsAllTag::where('product_id', $request->product_id)->leftJoin('all_tags', 'all_tags.all_tag_id', '=', 'connects_all_tags.all_tag_id')
        ->where('all_tags.type', 'sub_variation')->where('all_tags.value', $request->name)->first();
        $connect->all_tag_id = $allTagId;
        $connect->save();

        $subVariation->sub_variation = $request->new_name;
        $subVariation->save();

        return response()->json(['message' => 'Sub variation name updated successfully']);
    }

    public function getNextId()
    {
        $statement = DB::select("show table status like 'products'");
        return $statement[0]->Auto_increment;
    }
}
