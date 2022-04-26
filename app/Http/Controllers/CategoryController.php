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
        return SubCategory::with('filterStructues')->with('variation')->with('subVariation')->get();
    }

    public function addSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:sub_categories',
            'desc' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'type_values' => 'required_with:type|array',
            'type_values.*' => 'required_with:type|string|max:100',

            'variation_structure' => 'required|string|max:100|exists:filter_structures,name,type,variation',
            'sub_variation_structure' => 'nullable|string|max:100|exists:filter_structures,name,type,sub_variation',

            'filter_structures' => 'nullable|array',
            'filter_structures.*' => 'required_with:filter_structures|string|max:100|exists:filter_structures,name,type,filter',

            'is_group_variations' => 'required|boolean',
            'is_show_variation_as_product' => 'required|boolean',
        ]);
        // todo validate text_all_cap text_first_cap


        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->desc = $request->desc;
        $subCategory->type = $request->type;
        $subCategory->type_values = $request->type_values;

        $subCategory->variation_structure = FilterStructure::where('name', $request->variation_structure)->first()->filter_structure_id;
        $subCategory->sub_variation_structure = FilterStructure::where('name', $request->sub_variation_structure)->first()->filter_structure_id;

        $subCategory->is_group_variations = $request->is_group_variations;
        $subCategory->is_show_variation_as_product = $request->is_show_variation_as_product;

        // return $subCategory;
        $subCategory->save();

        if ($request->has('filter_structures')) {
            $subCategory->filter_structures = $request->filter_structures;
            foreach ($request->filter_structures as $filterName) {
                $filterStructureId = FilterStructure::where('name', $filterName)->first()->filter_structure_id;
                ConnectFilterSubCategory::firstOrCreate(['sub_category_id' => $subCategory->sub_category_id, 'filter_structure_id' => $filterStructureId]);
            }
        }

        return response()->json(['message' => 'Sub Category Added Successfully']);
    }

    public function addFilterToSubCategory(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'integer|exists:sub_categories,sub_category_id',
            'sub_category' => 'required_without:sub_category_id|string|max:100|exists:sub_categories,name',
            'filter_structure_id' => 'integer|exists:filter_structures,filter_structure_id',
            'filter_structure' => 'required_without:filter_structure_id|string|max:100|exists:filter_structures,name,type,filter',
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
            'filter_structure' => 'required_without:filter_structure_id|string|max:100|exists:filter_structures,name,type,filter',
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
}
