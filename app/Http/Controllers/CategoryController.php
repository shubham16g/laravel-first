<?php

namespace App\Http\Controllers;

use App\Models\ConnectFilterSubCategory;
use App\Models\FilterStructure;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class CategoryController extends Controller
{


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
        $subCategory->is_group_variations = $request->is_group_variations;
        $subCategory->is_show_variation_as_product = $request->is_show_variation_as_product;
        $subCategory->is_sub_variations = $request->is_sub_variations;
        $subCategory->variation_name = $request->variation_name;
        $subCategory->variation_postfix = $request->variation_postfix;
        $subCategory->save();

        return response()->json(['message' => 'Sub Category Added Successfully']);
    }

    public function addFilterToSubCategories(Request $request)
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
            'is_applicable' => 'required|boolean',
            'sub_categories' => 'nullable|array|min:1',
            'sub_categories.*' => 'required_with:sub_categories|integer|exists:sub_categories,sub_category_id',
            'sub_category_names' => 'reauired_without:sub_categories|array|min:1',
            'sub_category_names.*' => 'required_with:sub_category_names|string|max:100|exists:sub_categories,name',
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

        if ($request->sub_categories) {
            foreach ($request->sub_categories as $subCategoryId) {
                $connectFilterSubCategory = new ConnectFilterSubCategory();
                $connectFilterSubCategory->sub_category_id = $subCategoryId;
                $connectFilterSubCategory->filter_structure_id = $filter->filter_structure_id;
                $connectFilterSubCategory->save();
            }
        } else if ($request->sub_category_names) {
            foreach ($request->sub_category_names as $subCategoryName) {
                $subCategory = SubCategory::where('name', $subCategoryName)->first();
                $connectFilterSubCategory = new ConnectFilterSubCategory();
                $connectFilterSubCategory->sub_category_id = $subCategory->sub_category_id;
                $connectFilterSubCategory->filter_structure_id = $filter->filter_structure_id;
                $connectFilterSubCategory->save();
            }
        }

        return response()->json(['message' => 'Filter Added Successfully']);
    }
}
