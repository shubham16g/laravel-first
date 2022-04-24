<?php

namespace App\Http\Controllers;

use App\Models\ConnectFilterSubCategory;
use App\Models\FilterStructure;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class CategoryController extends Controller
{


    public function getSubCategories()
    {
        return SubCategory::with('filterStructues')->get();
    }

    public function addSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:sub_categories',
            'desc' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'type_values' => 'required_with:type|array',
            'type_values.*' => 'required_with:type|string|max:100',
            'is_group_variations' => 'required|boolean',
            'is_show_variation_as_product' => 'required|boolean',
            'is_sub_variations' => 'required|boolean',
            'variation_name' => 'required|string|max:100',
            'variation_postfix' => 'nullable|string|max:100'
        ]);


        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->desc = $request->desc;
        $subCategory->type = $request->type;
        $subCategory->type_values = $request->type_values;
        $subCategory->is_group_variations = $request->is_group_variations;
        $subCategory->is_show_variation_as_product = $request->is_show_variation_as_product;
        $subCategory->is_sub_variations = $request->is_sub_variations;
        $subCategory->variation_name = $request->variation_name;
        $subCategory->variation_postfix = $request->variation_postfix;
        $subCategory->save();

        return response()->json(['message' => 'Sub Category Added Successfully']);
    }

    public function addFilterStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:filter_structures',
            'input_type' => 'required|string|max:100|in:text,text_all_cap,text_first_cap,decimal,integer,radio,checkbox',
            'input_values' => 'nullable|array|if:filters.*.input_type=radio,checkbox',
            'input_values.*' => 'required_with:filters.*.input_values|string|max:100',
            'filter_type' => 'required|string|max:100|in:fixed,range,fixed_range',
            'postfix' => 'nullable|string|max:100',
            'prefix' => 'nullable|string|max:100',
            'is_required' => 'required|boolean',
            'is_applicable' => 'required|boolean'
        ]);

        $filter = new FilterStructure();
        $filter->name = $request->name;
        $filter->input_type = $request->input_type;
        $filter->input_values = $request->input_values;
        $filter->filter_type = $request->filter_type;
        $filter->postfix = $request->postfix;
        $filter->prefix = $request->prefix;
        $filter->is_required = $request->is_required;
        $filter->is_applicable = $request->is_applicable;
        $filter->save();

        return response()->json(['message' => 'Filter Added Successfully']);
    }

    public function addFilterToSubCategory(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'integer|exists:sub_categories,sub_category_id',
            'sub_category_name' => 'required_without:sub_category_id|string|max:100|exists:sub_categories,name',
            'filter_structure_id' => 'integer|exists:filter_structures,filter_structure_id',
            'filter_structure_name' => 'required_without:filter_structure_id|string|max:100|exists:filters,name',
        ]);

        $connect = new ConnectFilterSubCategory();

        if ($request->has('sub_category_id'))
            $connect->sub_category_id = $request->sub_category_id;
        else
            $connect->sub_category_id = SubCategory::where('name', $request->sub_category_name)->first()->sub_category_id;

        if ($request->has('filter_structure_id'))
            $connect->filter_structure_id = $request->filter_structure_id;
        else
            $connect->filter_structure_id = FilterStructure::where('name', $request->filter_structure_name)->first()->filter_structure_id;

        $connect->save();

        return response()->json(['message' => 'Filter Added Successfully']);
    }
}
