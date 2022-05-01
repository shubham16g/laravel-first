<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilterStructure;
use App\Models\SubVariationStructure;
use App\Models\VariationStructure;

class StructureController extends Controller
{
    public function addFilterStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'input_type' => 'required|string|max:100|in:string,string_all_cap,string_first_cap,numeric,integer,bool',
            'input_list' => 'nullable|array',
            'input_list.*' => 'required_with:input_list|' . preg_replace('/_.*/', '', $request->input_type) .'|max:100',
            'filter_type' => 'required_if:input_type,numeric,integer|string|max:100|in:fixed,range,fixed_range',
            'postfix' => 'nullable|string|max:100',
            'is_multiple_input' => 'boolean',
            'is_required' => 'boolean',
            'is_applicable' => 'boolean'
        ]);

        FilterStructure::store($request->name, $request->input_type, $request->input_list, $request->filter_type, $request->postfix, $request->is_multiple_input, $request->is_required, $request->is_applicable);
        return response()->json(['message' => 'Filter Structure Added Successfully']);
    }

    public function addVariationStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'input_type' => 'required|string|max:100|in:string,string_all_cap,string_first_cap,numeric,integer',
            'extras' => 'string|max:100|in:color,image',
            // 'input_list' => 'nullable|array',
            // 'input_list.*' => 'required_with:input_list|' . preg_replace('/_.*/', '', $request->input_type) . '|max:100',
            'filter_type' => 'required_if:input_type,numeric,integer|string|max:100|in:fixed,range,fixed_range',
            'postfix' => 'nullable|string|max:100',
        ]);

        VariationStructure::store($request->name, $request->input_type, $request->extras, $request->filter_type, $request->postfix);
        return response()->json(['message' => 'Variation Structure Added Successfully']);
    }

    public function addSubVariationStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'input_type' => 'required|string|max:100|in:string,string_all_cap,string_first_cap,numeric,integer',
            'extras' => 'string|max:100|in:color,image',
            'input_list' => 'nullable|array',
            'input_list.*' => 'required_with:input_list|' . preg_replace('/_.*/', '', $request->input_type) . '|max:100',
            'filter_type' => 'required_if:input_type,numeric,integer|string|max:100|in:fixed,range,fixed_range',
            'postfix' => 'nullable|string|max:100',
        ]);

        SubVariationStructure::store($request->name, $request->input_type, $request->input_list, $request->extras, $request->filter_type, $request->postfix);
        return response()->json(['message' => 'Sub Variation Structure Added Successfully']);
    }

    private function all($array, $type): bool
    {
        $arr = [
            'string' => 'is_string',
            'string_all_cap' => 'is_string',
            'string_first_cap' => 'is_string',
            'numeric' => 'is_numeric',
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
        ], 422);
    }
}
