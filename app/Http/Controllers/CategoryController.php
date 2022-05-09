<?php

namespace App\Http\Controllers;

use App\Models\BaseCategory;
use App\Models\Category;
use App\Models\ConnectFilterSubCategory;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class CategoryController extends Controller
{

    public function addBaseCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:base_categories',
            'icon' => 'required|string|max:255',
            'image' => 'required|string|max:255',
        ]);

        BaseCategory::store($request->name, $request->icon, $request->image);

        return response()->json(['message' => 'Base Category Added Successfully']);
    }

    public function addCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'base_category' => 'required|integer|exists:base_categories,base_category_id',
        ]);

        Category::store($request->name, $request->icon, $request->image, $request->base_category);

        return response()->json(['message' => 'Category Added Successfully']);
    }

    public function listBaseCategories()
    {
        return BaseCategory::all();
    }

    public function listCategories($baseCategoryId)
    {
        return Category::where('base_category_id', $baseCategoryId)->get();
    }


    public function getSubCategories()
    {
        return SubCategory::with('filterStructues')->with('variationStructure')->with('subVariationStructure')->get();
    }

    public function addSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:sub_categories',
            'desc' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'type_list' => 'required_with:type|array',
            'type_list.*' => 'required_with:type|string|max:100',

            'variation_structure' => 'required|integer|exists:variation_structures,variation_structure_id',
            'sub_variation_structure' => 'nullable|integer|exists:sub_variation_structures,sub_variation_structure_id',

            'filter_structures' => 'nullable|array',
            'filter_structures.*' => 'required_with:filter_structures|integer|exists:filter_structures,filter_structure_id',

            'is_group_variations' => 'required|boolean',
            'is_show_variation_as_product' => 'required|boolean',
        ]);



        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->desc = $request->desc;
        $subCategory->type = $request->type;
        $subCategory->type_list = $request->type_list;
        $subCategory->variation_structure = $request->variation_structure;
        $subCategory->sub_variation_structure = $request->sub_variation_structure;
        $subCategory->is_group_variations = $request->is_group_variations;
        $subCategory->is_show_variation_as_product = $request->is_show_variation_as_product;
        $subCategory->save();

        if ($request->has('filter_structures')) {
            $subCategory->filter_structures = $request->filter_structures;
            foreach ($request->filter_structures as $filterStructureId) {
                ConnectFilterSubCategory::firstOrCreate(['sub_category_id' => $subCategory->sub_category_id, 'filter_structure_id' => $filterStructureId]);
            }
        }

        return response()->json(['message' => 'Sub Category Added Successfully']);
    }

    public function addFilterToSubCategory(Request $request)
    {
        $request->validate([
            'sub_category' => 'integer|exists:sub_categories,sub_category_id',
            'filter_structure' => 'integer|exists:filter_structures,filter_structure_id',
        ]);

        ConnectFilterSubCategory::firstOrCreate(['sub_category_id' => $request->sub_category, 'filter_structure_id' => $request->filter_structure]);

        return response()->json(['message' => 'Filter Linked Successfully']);
    }

    public function removeFilterToSubCategory(Request $request)
    {
        $request->validate([
            'sub_category' => 'integer|exists:sub_categories,sub_category_id',
            'filter_structure' => 'integer|exists:filter_structures,filter_structure_id',
        ]);

        $data = ConnectFilterSubCategory::find(['sub_category_id' => $request->sub_category, 'filter_structure_id' => $request->filter_structure])->first();
        if ($data != null)
            $data->delete();
        return response()->json(['message' => 'Filter Unlinked Successfully']);
    }
}
