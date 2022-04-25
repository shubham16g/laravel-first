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


            'variation_name' => 'required|string|max:100',
            'variation_postfix' => 'nullable|string|max:100',
            'variation_input_type' => 'required|string|max:100|in:text,text_all_cap,text_first_cap,decimal,integer',
            'variation_input_list' => 'nullable|array',
            // if variation_input_type is text, then variation_input_list should be string else number
            'variation_input_list.*' => 'required_with:variation_input_list',

            'is_sub_variations' => 'required|boolean',
            /* 'sub_variation_name' => 'required_if:is_sub_variations,true|string|max:100',
            'sub_variation_postfix' => 'nullable|required_if:is_sub_variations,true|string|max:100',
            'sub_variation_input_type' => 'required_if:is_sub_variations,true|string|max:100|in:text,text_all_cap,text_first_cap,decimal,integer,list',
            'sub_variation_input_list' => 'required_if:sub_variation_input_type,list|array',
            'sub_variation_input_list.*' => 'required_with:sub_variation_input_list|string|max:100', */


            'is_group_variations' => 'required|boolean',
            'is_show_variation_as_product' => 'required|boolean',
        ]);



        if ($request->variation_input_list != null) {
            if (!$this->all($request->variation_input_list, $request->variation_input_type)) {
                return $this->errorInvalidGivenData('variation_input_list', 'all fields must be of type ' . $request->variation_input_type);
            }
        }

        // return $request->all();


        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->desc = $request->desc;
        $subCategory->type = $request->type;
        $subCategory->type_values = $request->type_values;

        $subCategory->variation_name = $request->variation_name;
        $subCategory->variation_postfix = $request->variation_postfix;
        $subCategory->variation_input_type = $request->variation_input_type;



        $subCategory->is_group_variations = $request->is_group_variations;
        $subCategory->is_show_variation_as_product = $request->is_show_variation_as_product;
        $subCategory->is_sub_variations = $request->is_sub_variations;

        return $subCategory;
        // $subCategory->save();

        return response()->json(['message' => 'Sub Category Added Successfully']);
    }

    public function addFilterStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:filter_structures',
            'input_type' => 'required|string|max:100|in:text,text_all_cap,text_first_cap,decimal,integer,list_radio,list_checkbox',
            'input_list' => 'nullable|array|if:input_type=list_radio,list_checkbox',
            'input_list.*' => 'required_with:input_list|string|max:100',
            'filter_type' => 'required|string|max:100|in:fixed,range,fixed_range',
            'postfix' => 'nullable|string|max:100',
            'prefix' => 'nullable|string|max:100',
            'is_required' => 'required|boolean',
            'is_applicable' => 'required|boolean'
        ]);

        $filter = new FilterStructure();
        $filter->name = $request->name;
        $filter->input_type = $request->input_type;
        $filter->input_list = $request->input_list;
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
            'sub_category' => 'required_without:sub_category_id|string|max:100|exists:sub_categories,name',
            'filter_structure_id' => 'integer|exists:filter_structures,filter_structure_id',
            'filter_structure' => 'required_without:filter_structure_id|string|max:100|exists:filter_structures,name',
        ]);

        $subCategoryId = 0;
        $filterStructureId = 0;

        if ($request->has('sub_category_id'))
            $subCategoryId = $request->sub_category_id;
        else
            $subCategoryId = SubCategory::where('name', $request->sub_category)->first()->sub_category_id;

        if ($request->has('filter_structure_id'))
            $filterStructureId = $request->filter_structure_id;
        else
            $filterStructureId = FilterStructure::where('name', $request->filter_structure)->first()->filter_structure_id;

        ConnectFilterSubCategory::firstOrCreate(['sub_category_id' => $subCategoryId, 'filter_structure_id' => $filterStructureId]);

        return response()->json(['message' => 'Filter Added Successfully']);
    }

    public function removeFilterToSubCategory(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'integer|exists:sub_categories,sub_category_id',
            'sub_category' => 'required_without:sub_category_id|string|max:100|exists:sub_categories,name',
            'filter_structure_id' => 'integer|exists:filter_structures,filter_structure_id',
            'filter_structure' => 'required_without:filter_structure_id|string|max:100|exists:filter_structures,name',
        ]);

        $subCategoryId = 0;
        $filterStructureId = 0;

        if ($request->has('sub_category_id'))
            $subCategoryId = $request->sub_category_id;
        else
            $subCategoryId = SubCategory::where('name', $request->sub_category)->first()->sub_category_id;

        if ($request->has('filter_structure_id'))
            $filterStructureId = $request->filter_structure_id;
        else
            $filterStructureId = FilterStructure::where('name', $request->filter_structure)->first()->filter_structure_id;

        $data = ConnectFilterSubCategory::find(['sub_category_id' => $subCategoryId, 'filter_structure_id' => $filterStructureId])->first();
        if ($data != null)
            $data->delete();
        return response()->json(['message' => 'Filter Removed Successfully']);
    }







    private function all($array, $type): bool
    {
        $arr = [
            'text' => 'is_string',
            'text_all_cap' => 'is_string',
            'text_first_cap' => 'is_string',
            'decimal' => 'is_numeric',
            'integer' => 'is_int',
        ];
        return array_filter($array, $arr[$type]) === $array;
    }

    private function errorInvalidGivenData($field, $msg)
    {
        return response()->json([
            "message" => "The given data was invalid.",
            "errors" => [
                $field => [
                    $msg
                ]
            ]
        ],422);
    }
}
