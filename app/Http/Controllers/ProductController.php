<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
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
}
