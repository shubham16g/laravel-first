<?php

namespace App\Http\Controllers;

use App\Models\ConnectFilterSubCategory;
use App\Models\FormInputStructure;
use Illuminate\Http\Request;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{

    public function getSubCategoryStructure(Request $request, $subCategoryId)
    {
        $request->merge(['sub_category' => $subCategoryId]);
        $request->validate([
            'sub_category' => 'required|integer|exists:sub_categories,sub_category_id',
        ]);

        // $subCategory = SubCategory::with('variationStructure')
        //     ->with('subVariationStructure')
        //     // ->select('sub_categories.sub_category_id', 'sub_categories.name', 'sub_categories.type', 'sub_categories.type_list', 'sub_categories.variation_structure', 'sub_categories.sub_variation_structure')
        //     // ->leftJoin('connect_sub_categories', 'connect_sub_categories.sub_category_id', '=', 'sub_categories.sub_category_id')
        //     ->with('filterStructues')
        //     ->find($request->sub_category);

        // $res = $subCategory->toArray();
        // unset($res['sub_category_id']);

        $subCategory = SubCategory::find($request->sub_category);

        $filterStructures = FormInputStructure::
        leftJoin('connect_filter_sub_categories', 'connect_filter_sub_categories.filter_structure', '=', 'form_input_structures.form_input_structure_id')
            ->where('connect_filter_sub_categories.sub_category_id', $subCategoryId)
            ->select(['form_input_structures.*', 'connect_filter_sub_categories.name'])
            ->get();



        return [
            'variation_structure' => $subCategory->variationStructure,
            'sub_variation_structure' => $subCategory->subVariationStructure,
            'filter_structures' => $filterStructures,
        ];
    }

    public function getSubCategories(Request $request, $categoryId)
    {
        $request->merge(['category_id' => $categoryId]);
        $request->validate([
            'category_id' => 'required|integer|exists:categories,category_id',
        ]);

        return SubCategory::leftJoin('connect_sub_categories', 'connect_sub_categories.sub_category_id', '=', 'sub_categories.sub_category_id')
            ->join('categories', 'categories.category_id', '=', 'connect_sub_categories.category_id')
            ->select('sub_categories.sub_category_id', 'sub_categories.name', 'sub_categories.desc', 'sub_categories.icon', 'sub_categories.image', 'connect_sub_categories.type')
            ->where('connect_sub_categories.category_id', $categoryId)
            ->get();

        // return SubCategory::with('filterStructues')->with('variationStructure')->with('subVariationStructure')->get();
    }

    public function addSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:sub_categories',
            'desc' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'type_list' => 'required_with:type|array',
            'type_list.*' => 'required_with:type|string|max:100',

            'variation_structure' => 'required|integer|exists:form_input_structures,form_input_structure_id',
            'sub_variation_structure' => 'nullable|integer|exists:form_input_structures,form_input_structure_id',

            'filter_structures' => 'nullable|array',
            'filter_structures.*.id' => 'required_with:filter_structures|integer|exists:form_input_structures,form_input_structure_id',
            'filter_structures.*.name' => 'required_with:filter_structures|string|max:100',
            'filter_structures.*.is_applicable' => 'boolean',

            'is_group_variations' => 'required|boolean',
            'is_show_variation_as_product' => 'required|boolean',
        ]);



        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->desc = $request->desc;
        $subCategory->icon = $request->icon;
        $subCategory->image = $request->image;
        $subCategory->type = $request->type;
        $subCategory->type_list = $request->type_list;
        $subCategory->variation_structure = $request->variation_structure;
        $subCategory->sub_variation_structure = $request->sub_variation_structure;
        $subCategory->is_group_variations = $request->is_group_variations;
        $subCategory->is_show_variation_as_product = $request->is_show_variation_as_product;
        $subCategory->save();

        if ($request->has('filter_structures')) {
            foreach ($request->filter_structures as $filterStructure) {
                $is_applicable = isset($filterStructure['is_applicable']) ? $filterStructure['is_applicable'] : false;
                ConnectFilterSubCategory::firstOrCreate([
                    'sub_category_id' => $subCategory->sub_category_id,
                    'filter_structure' => $filterStructure['id'],
                    'name' => $filterStructure['name'],
                    'is_applicable' => $is_applicable,
                ]);
            }
        }

        return response()->json(['message' => 'Sub Category Added Successfully']);
    }

    // todo
    public function addFilterToSubCategory(Request $request)
    {
        $request->validate([
            'sub_category' => 'integer|exists:sub_categories,sub_category_id',
            'filter_structure' => 'integer|exists:filter_structures,filter_structure_id',
        ]);

        ConnectFilterSubCategory::firstOrCreate(['sub_category_id' => $request->sub_category, 'filter_structure_id' => $request->filter_structure]);

        return response()->json(['message' => 'Filter Linked Successfully']);
    }

    // 
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
