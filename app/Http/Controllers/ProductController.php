<?php

namespace App\Http\Controllers;

use App\Models\AllTag;
use App\Models\ConnectsAllTag;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubVariation;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    // todo create new all_tags type table for storing non-quering tags
    public function list(Request $request)
    {

        $perPage = $request->per_page ?? 18;
        $category = $request->category;
        $sub_category = $request->sub_category;
        $variation = $request->variation;
        $sub_variation = $request->sub_variation;
        $s = $request->s;

        // $color = 'blue';
        // $s = 'desc';
        // $quantity = "10";

        $products = Product::with('allTags')->with('subVariations')
            ->leftJoin('connects_all_tags', 'connects_all_tags.product_id', '=', 'products.product_id')
            ->join('all_tags', 'all_tags.all_tag_id', '=', 'connects_all_tags.all_tag_id')
            ->select('products.*')
            ->groupBy('products.distinct_id');
        // ->where('all_tags.value', '=', 'M')

        if ($sub_category != null || $s != null || $category != null || $variation != null|| $sub_variation != null) {
            $products->orderBy(DB::raw('COUNT(connects_all_tags.all_tag_id)'), 'desc');
        }

        if ($category != null && strlen($category) > 2) {
            $products->orWhere(function ($query) use ($category) {
                $query->where('all_tags.value', '=', "$category")->where('all_tags.type', '=', "category");
            });
        }

        if ($sub_category != null && strlen($sub_category) > 2) {
            $products->orWhere(function ($query) use ($sub_category) {
                $query->where('all_tags.value', '=', "$sub_category")->where('all_tags.type', '=', "sub_category");
            });
        }

        if ($variation != null && strlen($variation)) {
            $products->orWhere(function ($query) use ($variation) {
                $query->where('all_tags.value', '=', "$variation")->where('all_tags.type', '=', "varia$variation");
            });
        }

        if ($sub_variation != null && strlen($sub_variation)) {
            $products->orWhere(function ($query) use ($sub_variation) {
                $query->where('all_tags.value', '=', "$sub_variation")->where('all_tags.type', '=', "sub_variation");
            });
        }



        if ($s != null && strlen($s) > 0) {
            $orderByArray = [];
            $subQueries = explode(' ', $s, 5);

            for ($i = 0; $i < count($subQueries); $i++) {
                $sq = $subQueries[$i];
                $products->orWhere('all_tags.value', 'like', "%$sq%");
                $orderByArray[0][] = " when all_tags.value LIKE '$sq'  then ";
                $orderByArray[1][] = " when all_tags.value LIKE '$sq%'  then ";
                $orderByArray[2][] = " when all_tags.value LIKE '%$sq%'  then ";
            }

            // check if orderByArray is not empty
            if (sizeof($orderByArray) > 0) {

                $orderByString = '';
                $counter = 1;
                foreach ($orderByArray as $value) {
                    foreach ($value as $str) {
                        $orderByString .= $str . ' ' . $counter++;
                    }
                }
                $products->orderByRaw("case " . $orderByString . " else $counter end");
            }
        }

        // if ($request->order_by == 'downloads') {
        //     $products->orderBy('downloads', "DESC");
        // }
        $request->order_by = 'created_at';

        return response()->json($products->paginate($perPage));
    }

    public function addProduct(Request $request)
    {

        // todo validation rule
        $data = $request->validate([

            'name' => 'required|string|max:100',
            'desc' => 'required|string|max:255',

            'sub_category' => 'required|string|max:100|exists:sub_categories,name',

            'variation' => 'required|string|max:50',

            'sub_variations' => 'nullable|array',
            'sub_variations.*.name' => 'required_with:sub_variations|string|max:50',
            'sub_variations.*.price' => 'required_with:sub_variations|numeric|min:0',
            'sub_variations.*.mrp' => 'required_with:sub_variations|gt:sub_variations.*.price|numeric|min:0',


            'price' => 'required_without:sub_variations.0.price|numeric|min:0',
            'mrp' => 'required_without:sub_variations.0.mrp|gt:price|numeric|min:0',

            'tags' => 'required|array',
            'tags.*' => 'required|max:50',

            'filters' => 'required|array',
            'filters.*.name' => 'required|exists:filter_structures,name',

        ]);

        $subCategory = SubCategory::with('filterStructues')->where('name', $data['sub_category'])->first();


        if ($subCategory->is_sub_variations) {
            if (!$request->sub_variations) {
                return "wtfr";
            } else {
                foreach ($request->sub_variations as $sub_variation) {
                    // array contains
                    if (!in_array($sub_variation['name'], $subCategory->sub_variation_input_list)) {
                        return "wtf";
                    }
                }
            }
        }
        // todo check if sub_category group system and distinct system

        return 'done';

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

        if (isset($data['sub_variations'])) {
            $minMrp = 0;
            $minPrice = 0;
            $maxMrp = 0;
            $maxPrice = 0;
            foreach ($data['sub_variations'] as $sub_variation) {
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

    public function getNextId()
    {
        $statement = DB::select("show table status like 'products'");
        return $statement[0]->Auto_increment;
    }
}
