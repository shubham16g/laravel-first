<?php

namespace Database\Seeders;

use App\Models\FormInputStructure;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private const filterStructureData = [
        [
            'name' => 'Name',
            'input_type' => 'string',
            'string_capitalization' => 'sentences',
            'filter_type' => 'fixed',
            'is_required' => true,
        ],
        [
            'name' => 'Description',
            'input_max_lines' => 5,
            'input_min_lines' => 2,
            'input_type' => 'string',
            'string_capitalization' => 'sentences',
            'filter_type' => 'fixed',
            'is_required' => true,
        ],
        [
            'name' => 'Tags',
            'input_max_lines' => 5,
            'input_min_lines' => 2,
            'input_type' => 'string',
            'string_capitalization' => 'none',
            'filter_type' => 'fixed',
            'is_required' => false,
        ],
        [
            'name' => 'Price',
            'input_type' => 'numeric',
            'filter_type' => 'range',
        ],
        [
            'name' => 'MRP',
            'input_type' => 'numeric',
            'filter_type' => 'range',
        ],
        [
            'name' => 'Size',
            'input_type' => 'list',
            'input_list' => ['S', 'M', 'L', 'XL', 'XXL'],
            'filter_type' => 'fixed',
            'is_multiple_input' => true,
        ],
        [
            'name' => 'Color Code',
            'input_type' => 'color',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Color',
            'input_type' => 'string',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Storage Type',
            'input_type' => 'string',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Brand',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true,
        ],
        [
            'name' => 'Manufacturer',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Processor Type',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Processor Model',
            'input_type' => 'string',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => false
        ],
        [
            'name' => 'Laptop Usage',
            'input_type' => 'list',
            'input_list' => ['Gaming', 'Office', 'Multimedia', 'Student'],
            'suffix' => null,
            'filter_type' => 'fixed',
            'is_multiple_input' => true,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'RAM',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'suffix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'RAM Memory Technology',
            'input_type' => 'string',
            'string_capitalization' => 'characters',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Processor Brand',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Processor Speed',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'range',
            'suffix' => ' GHz',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Processor Count',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => false,
        ],
        [
            'name' => 'Maximum RAM Supported',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => false,
        ],
        [
            'name' => 'Operating System',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Storage Type',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Storage Size',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'range',
            'suffix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Graphics Card',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Brightness',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'range',
            'suffix' => 'Nits',
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => true
        ],
        [
            'name' => 'Display Size',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'range',
            'suffix' => ' inch',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Display Resoulution',
            'input_type' => 'string',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => ' Pixels',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Biometric Security',
            'input_type' => 'string',
            'string_capitalization' => 'words',
            'input_list' => null,
            'filter_type' => 'fixed',
            'suffix' => null,
            'is_multiple_input' => true,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Laptop Graphics Memory',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'suffix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => false
        ],
        [
            'name' => 'Laptop Battery Life',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'suffix' => 'h',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'name' => 'Laptop Weight',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'suffix' => ' kg',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ]
    ];

    public const subCategories = [];

    public function run()
    {
        foreach ((object)self::filterStructureData as $var) {
            $this->store((object)$var);

            // FilterStructure::store($var->name, $var->input_type, $var->input_list, $var->filter_type, $var->suffix, $var->is_multiple_input,$var->is_required, $var->is_applicable, $var->id);
        }
    }

    private function store(object $request){
        if(FormInputStructure::where('name', $request->name)->exists()){
            return;
        }
        $formInputStructure = new FormInputStructure();
        $formInputStructure->name = $request->name;
        $formInputStructure->input_type = $request->input_type;
        if ($request->input_type == 'string' || $request->input_type == 'email' || $request->input_type == 'phone' || $request->input_type == 'password') {
            if (isset($request->input_max_length))
                $formInputStructure->input_max_length = $request->input_max_length;
            if (isset($request->input_min_length))
                $formInputStructure->input_min_length = $request->input_min_length;
            if (isset($request->input_max_lines))
                $formInputStructure->input_max_lines = $request->input_max_lines;
            if (isset($request->input_min_lines))
                $formInputStructure->input_min_lines = $request->input_min_lines;
        }
        if ($request->input_type == 'string' && isset($request->string_capitalization))
        $formInputStructure->string_capitalization = $request->string_capitalization;
        $formInputStructure->input_list = $request->input_list ?? null;
        if ($request->filter_type != null && ($request->input_type == 'numreic' || $request->input_type == 'integer'))
        $formInputStructure->filter_type = $request->filter_type;
        $formInputStructure->suffix = $request->suffix ?? null;
        $formInputStructure->prefix = $request->prefix ?? null;
        $formInputStructure->is_multiple_input = $request->is_multiple_input ?? false;
        $formInputStructure->is_required = $request->is_required ?? true;
        $formInputStructure->save();
    }
}
