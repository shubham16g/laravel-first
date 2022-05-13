<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormInputStructure;

class StructureController extends Controller
{
    public function addFormInputStructure(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:form_input_structures',
            'input_type' => 'required|string|max:100|in:string,numeric,integer,bool,email,phone,datetime,password,list,color,image',
            'input_max_length' => 'integer|min:0',
            'input_min_length' => 'integer|min:0',
            'input_max_lines' => 'numeric|min:0',
            'input_min_lines' => 'numeric|min:0',
            'string_capitalization' => 'string|max:100|in:none,characters,words,sentences',
            'input_list' => $request->input_type == 'list'? 'required' : 'nullable' . '|array',
            'input_list.*' => 'required_with:input_list|max:100',
            'filter_type' => 'required_if:input_type,numeric,integer|string|max:100|in:fixed,range,fixed_range',
            'suffix' => 'nullable|string|max:100',
            'prefix' => 'nullable|string|max:100',
            'is_multiple_input' => 'boolean',
            'is_required' => 'boolean',
            // 'is_applicable' => 'boolean'
        ]);
        
        $formInputStructure = new FormInputStructure();
        $formInputStructure->name = $request->name;
        $formInputStructure->input_type = $request->input_type;
        if ($request->input_type == 'string' || $request->input_type == 'email' || $request->input_type == 'phone' || $request->input_type == 'password') {   
            if ($request->input_max_length)
                $formInputStructure->input_max_length = $request->input_max_length;
            if ($request->input_min_length)
                $formInputStructure->input_min_length = $request->input_min_length;
            if ($request->input_max_lines)
                $formInputStructure->input_max_lines = $request->input_max_lines;
            if ($request->input_min_lines)
                $formInputStructure->input_min_lines = $request->input_min_lines;
        }
        if ($request->input_type == 'string') 
            $formInputStructure->string_capitalization = $request->string_capitalization;
        $formInputStructure->input_list = $request->input_list;
        if ($request->filter_type != null && ($request->input_type == 'numreic' || $request->input_type == 'integer'))
            $formInputStructure->filter_type = $request->filter_type;
        $formInputStructure->suffix = $request->suffix;
        $formInputStructure->prefix = $request->prefix;
        $formInputStructure->is_multiple_input = $request->is_multiple_input;
        $formInputStructure->is_required = $request->is_required;
        // $formInputStructure->is_applicable = $request->is_applicable;
        $formInputStructure->save();
        return response()->json(['message' => 'Form Input Structure Added Successfully']);
    }

    public function getFormInputStructures()
    {
        return FormInputStructure::all();
    }

    // public function addVariationStructure(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'input_type' => 'required|string|max:100|in:string,string_all_cap,string_first_cap,numeric,integer',
    //         'extras' => 'string|max:100|in:color,image',
    //         // 'input_list' => 'nullable|array',
    //         // 'input_list.*' => 'required_with:input_list|' . preg_replace('/_.*/', '', $request->input_type) . '|max:100',
    //         'filter_type' => 'required_if:input_type,numeric,integer|string|max:100|in:fixed,range,fixed_range',
    //         'postfix' => 'nullable|string|max:100',
    //     ]);

    //     VariationStructure::store($request->name, $request->input_type, $request->extras, $request->filter_type, $request->postfix);
    //     return response()->json(['message' => 'Variation Structure Added Successfully']);
    // }

    // public function addSubVariationStructure(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:100',
    //         'input_type' => 'required|string|max:100|in:string,string_all_cap,string_first_cap,numeric,integer',
    //         'extras' => 'string|max:100|in:color,image',
    //         'input_list' => 'nullable|array',
    //         'input_list.*' => 'required_with:input_list|' . preg_replace('/_.*/', '', $request->input_type) . '|max:100',
    //         'filter_type' => 'required_if:input_type,numeric,integer|string|max:100|in:fixed,range,fixed_range',
    //         'postfix' => 'nullable|string|max:100',
    //     ]);

    //     SubVariationStructure::store($request->name, $request->input_type, $request->input_list, $request->extras, $request->filter_type, $request->postfix);
    //     return response()->json(['message' => 'Sub Variation Structure Added Successfully']);
    // }

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
