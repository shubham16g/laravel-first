<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilterStructure;

class FilterStructureController extends Controller
{
    public function addFilterStructure(Request $request, string $type)
    {

        $request->merge([
            'type' => $type,
        ]);
        $request->validate([
            'name' => 'required|string|max:100|unique:filter_structures',
            'type' => 'required|string|max:100|in:filter,variation,sub-variation',
            'input_type' => 'required|string|max:100|in:string,string_all_cap,string_first_cap,numeric,integer',
            'input_list' => 'nullable|array',
            'input_list.*' => 'required_with:input_list',
            'filter_type' => 'required|string|max:100|in:fixed,range,fixed_range',
            'postfix' => 'nullable|string|max:100',
            'prefix' => 'nullable|string|max:100',
            'is_multiple_input' => 'boolean',
            'is_required' => 'boolean',
            'is_applicable' => 'boolean'
        ]);

        // todo improve validation technique
        if ($request->input_list != null) {
            if (!$this->all($request->input_list, $request->input_type)) {
                return $this->errorInvalidGivenData('input_list', 'all fields must be of type ' . $request->input_type);
            }
        }


        // todo validate text_all_cap text_first_cap

        $filter = new FilterStructure();
        $filter->name = $request->name;
        $filter->type = str_replace("-", "_", $type);
        $filter->input_type = $request->input_type;
        if ($request->type != 'variation')
            $filter->input_list = $request->input_list;
        $filter->filter_type = $request->filter_type;
        $filter->postfix = $request->postfix;
        $filter->prefix = $request->prefix;

        if ($request->type == 'filter') {
            if ($request->has('is_multiple_input'))
                $filter->is_multiple_input = $request->is_multiple_input;
            if ($request->has('is_required'))
                $filter->is_required = $request->is_required;
            if ($request->has('is_applicable'))
                $filter->is_applicable = $request->is_applicable;
        }
        $filter->save();

        return response()->json(['message' => 'Filter Added Successfully']);
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
