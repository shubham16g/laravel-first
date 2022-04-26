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
}
