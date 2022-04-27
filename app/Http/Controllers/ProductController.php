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

        if ($sub_category != null || $s != null || $category != null || $variation != null || $sub_variation != null) {
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

        $request->validate([
            'sub_category' => 'required|string|max:100|exists:sub_categories,name',
        ]);

        $subCategory = SubCategory::with('filterStructues')->with('variationStructure')->with('subVariationStructure')->where('name', $request->sub_category)->first();

        $variationInputType = preg_replace('/_.*/', '', $subCategory->variationStructure->input_type);
        $subVariationInputType = 'string';
        $subVariationInputList = '';
        if ($subCategory->subVariationStructure != null) {
            $subVariationInputType = preg_replace('/_.*/', '', $subCategory->subVariationStructure->input_type);
            if ($subCategory->subVariationStructure->input_list != null) {
                $subVariationInputList = '|in:' . implode(',', $subCategory->subVariationStructure->input_list);
            }
        }

        $filterRules = [
            'filters' => 'required|array',
            'filters.Brand' => 'required|string|max:50',
        ];
        if ($subCategory->filterStructues != null) {
            $filterRules['filters'] = 'required|array';
            foreach ($subCategory->filterStructues as $filterStructure) {
                $required = $filterStructure->is_required ? 'required|' : 'nullable|';
                $inputList = ($filterStructure->input_list != null) ? '|in:' . implode(',', $filterStructure->input_list) : '';
                $filterRules['filters.' . $filterStructure->name] = $required . preg_replace('/_.*/', '', $filterStructure->input_type) . $inputList;
            }
        }
        // foreach ($subCategory->filterStructues as $filterStructure) {
        // $filterRules[$filterStructure->name] = 'required|' . $filterStructure->input_type . $filterStructure->input_list;
        // }

        $request->merge([
            'sub_variation' => $subCategory->sub_variation_structure != null ? 'mandatory' : 'not_mandatory',
        ]);


        $request->validate([
            'name' => 'required|string|max:100',
            'desc' => 'required|string|max:255',

            'tags' => 'array',
            'tags.*' => 'required_with:tags|max:50',

            'variation' => "required|$variationInputType|max:50",

            'sub_variations' => 'required_if:sub_variation,mandatory|array',
            'sub_variations.*.value' => "required_with:sub_variations|$subVariationInputType|max:50$subVariationInputList", //value instead of name
            'sub_variations.*.price' => 'required_with:sub_variations|numeric|min:0',
            'sub_variations.*.mrp' => 'required_with:sub_variations|gt:sub_variations.*.price|numeric|min:0',


            'price' => 'required_if:sub_variation,not_mandatory|numeric|min:0',
            'mrp' => 'required_with:price|gt:price|numeric|min:0',



        ] + $filterRules);

        $allTags = [];

        $allTags[AllTag::firstOrCreate(['value' => $request->name, 'type' => 'name'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $request->desc, 'type' => 'desc'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $request->sub_category, 'type' => 'sub_category'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $request->variation, 'type' => 'variation'])->all_tag_id] = true;
        // also need the unit of variation
        if ($request->tags)
            foreach ($request->tags as $tag) {
                $allTags[AllTag::firstOrCreate(['value' => $tag, 'type' => 'tag'])->all_tag_id] = true;
            }

        $product = new Product();
        $currentProductID = $this->getNextId();
        $product->group_id = $currentProductID;
        $product->distinct_id = $currentProductID;

        $product->save();

        if ($request->filters)
            foreach ($request->filters as $filterName => $filterValue) {
                if($filterValue == null) continue;
                $allTags[AllTag::firstOrCreate(['value' => $filterValue, 'type' => $filterName])->all_tag_id] = true;
            }


        if (isset($request->sub_variations)) {
            $minMrp = 0;
            $minPrice = 0;
            $maxMrp = 0;
            $maxPrice = 0;
            foreach ($request->sub_variations as $sub_variation) {
                $allTags[AllTag::firstOrCreate(['value' => $sub_variation['value'], 'type' => 'sub_variation'])->all_tag_id] = true;
                $subVariation = new SubVariation();
                $subVariation->product_id = $currentProductID;
                $subVariation->sub_variation = $sub_variation['value'];
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
            $product->min_mrp = $request->mrp;
            $product->min_price = $request->price;
            $product->max_mrp = $request->mrp;
            $product->max_price = $request->price;
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
