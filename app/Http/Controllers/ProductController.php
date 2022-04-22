<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AllTag;
use App\Models\ConnectsAllTag;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    public function list(Request $request)
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([

            'name' => 'required|string|max:100',
            'desc' => 'required|string|max:255',

            'mrp' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',

            'sub_category' => 'required|string|max:100',

            'color' => 'nullable|string|max:10',
            'size' => 'nullable|string|max:10',
            'quantity' => 'nullable|string|max:10',

            'tags' => 'required|array',
            'tags.*' => 'required|max:50',

        ]);

        // todo check if sub_category group system and distinct system

        $allTags = [];

        $allTags[AllTag::firstOrCreate(['value' => $data['name'], 'type' => 'name'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $data['desc'], 'type' => 'desc'])->all_tag_id] = true;
        $allTags[AllTag::firstOrCreate(['value' => $data['sub_category'], 'type' => 'sub_category'])->all_tag_id] = true;
        if (isset($data['color']))
        $allTags[AllTag::firstOrCreate(['value' => $data['color'], 'type' => 'color'])->all_tag_id] = true;
        if (isset($data['size']))
        $allTags[AllTag::firstOrCreate(['value' => $data['size'], 'type' => 'size'])->all_tag_id] = true;
        if (isset($data['quantity']))
        $allTags[AllTag::firstOrCreate(['value' => $data['quantity'], 'type' => 'quantity'])->all_tag_id] = true;

        foreach ($data['tags'] as $tag) {
            $allTags[AllTag::firstOrCreate(['value' => $tag, 'type' => 'tag'])->all_tag_id] = true;
        }

        $product = new Product();
        $product->mrp = $data['mrp'];
        $product->price = $data['price'];
        $product->group_id = $this->getNextId();
        $product->distinct_id = $this->getNextId();

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
