<?php

namespace App\Http\Controllers;

use App\Models\BaseCategory;
use App\Models\Category;
use App\Models\ConnectSubCategory;
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


    /* public function getSubCategories(Request $request, $categoryId)
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
 */

    public function linkSubCategoryToCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|integer|exists:categories,category_id',
            'sub_category' => 'required|integer|exists:sub_categories,sub_category_id',
            // 'type' => 'nullable|string|max:100',
        ]);


        $subCategory = SubCategory::find($request->sub_category);
        $typeIn = '';
        if ($subCategory->type_list != null) {
            $typeIn = '|in:' . implode(',', $subCategory->type_list);
        }

        $request->validate([
            'type' => 'nullable|string|max:100' . $typeIn,
        ]);

        ConnectSubCategory::firstOrCreate([
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category,
            'type' => $request->type,
        ]);

        return response()->json(['message' => 'Sub Category Linked Successfully']);
        // $connect->save();

    }

    /* public function addFilterToSubCategory(Request $request)
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
    } */
}
