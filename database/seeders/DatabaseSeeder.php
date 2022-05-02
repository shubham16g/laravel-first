<?php

namespace Database\Seeders;

use App\Models\FilterStructure;
use App\Models\SubVariationStructure;
use App\Models\VariationStructure;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    private const variationStructureData = [
        [
            'id' => 1,
            'name' => 'Color',
            'input_type' => 'string',
            'extras' => 'color',
            'filter_type' => 'fixed',
            'postfix' => null,
        ],
        [
            'id' => 2,
            'name' => 'Color',
            'input_type' => 'string',
            'extras' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
        ],
    ];

    private const subVariationStructureData = [
        [
            'id' => 1,
            'name' => 'Size',
            'input_type' => 'string_all_cap',
            'input_list' => ['S', 'M', 'L', 'XL', 'XXL'],
            'extras' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
        ],
        [
            'id' => 2,
            'name' => 'Storage Type',
            'input_type' => 'string',
            'input_list' => null,
            'extras' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
        ],
    ];

    private const filterStructureData = [
        [
            'id' => 1,
            'name' => 'Brand',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true,
        ],
        [
            'id' => 2,
            'name' => 'Manufacturer',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 3,
            'name' => 'Processor Type',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 4,
            'name' => 'Processor Model',
            'input_type' => 'string',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => false
        ],
        [
            'id' => 5,
            'name' => 'Laptop Usage',
            'input_type' => 'string_first_cap',
            'input_list' => ['Gaming', 'Office', 'Multimedia', 'Student'],
            'postfix' => null,
            'filter_type' => 'fixed',
            'is_multiple_input' => true,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 6,
            'name' => 'RAM',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'postfix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 7,
            'name' => 'RAM Memory Technology',
            'input_type' => 'string_all_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 8,
            'name' => 'Processor Brand',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 9,
            'name' => 'Processor Speed',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'range',
            'postfix' => ' GHz',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 10,
            'name' => 'Processor Count',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => false,
        ],
        [
            'id' => 11,
            'name' => 'Maximum RAM Supported',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => false,
        ],
        [
            'id' => 12,
            'name' => 'Operating System',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 13,
            'name' => 'Storage Type',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 14,
            'name' => 'Storage Size',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'range',
            'postfix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 15,
            'name' => 'Graphics Card',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 16,
            'name' => 'Brightness',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'range',
            'postfix' => 'Nits',
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => true
        ],
        [
            'id' => 17,
            'name' => 'Display Size',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'range',
            'postfix' => ' inch',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 18,
            'name' => 'Display Resoulution',
            'input_type' => 'string',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => ' Pixels',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 19,
            'name' => 'Biometric Security',
            'input_type' => 'string_first_cap',
            'input_list' => null,
            'filter_type' => 'fixed',
            'postfix' => null,
            'is_multiple_input' => true,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 20,
            'name' => 'Laptop Graphics Memory',
            'input_type' => 'integer',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'postfix' => ' GB',
            'is_multiple_input' => false,
            'is_required' => false,
            'is_applicable' => false
        ],
        [
            'id' => 21,
            'name' => 'Laptop Battery Life',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'postfix' => 'h',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ],
        [
            'id' => 22,
            'name' => 'Laptop Weight',
            'input_type' => 'numeric',
            'input_list' => null,
            'filter_type' => 'fixed_range',
            'postfix' => ' kg',
            'is_multiple_input' => false,
            'is_required' => true,
            'is_applicable' => true
        ]
    ];


    public function run()
    {
        foreach (self::variationStructureData as $var) {
            $var = (object)$var;
            VariationStructure::store($var->name, $var->input_type, $var->extras, $var->filter_type, $var->postfix, $var->id);
        }
        foreach ((object)self::subVariationStructureData as $var) {
            $var = (object)$var;
            SubVariationStructure::store($var->name, $var->input_type, $var->input_list, $var->extras, $var->filter_type, $var->postfix, $var->id);
        }
        foreach ((object)self::filterStructureData as $var) {
            $var = (object)$var;
            FilterStructure::store($var->name, $var->input_type, $var->input_list, $var->filter_type, $var->postfix, $var->is_multiple_input,$var->is_required, $var->is_applicable, $var->id);
        }
    }
}
