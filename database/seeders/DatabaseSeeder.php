<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    private const variationStructData = [
        [
            'name' => 'Color',
            'input_type' => 'string',
            'filter_type' => 'fixed',
            'type' => 'variation'
        ],
        [
            'name' => 'Size',
            'input_type' => 'string_all_cap',
            'input_list' => ['S', 'M', 'L', 'XL', 'XXL'],
            'filter_type' => 'fixed',
            'type' => 'sub_variation'
        ],
        [
            'name' => 'Brand',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Manufacturer',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
            'is_applicable' => false
        ],
        [
            'name' => 'Processor Type',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Processor Model',
            'input_type' => 'string',
            'filter_type' => 'fixed',
            'is_applicable' => false
        ],
        [
            'name' => 'Laptop Usage',
            'input_type' => 'string_first_cap',
            'input_list' => ['Gaming', 'Office', 'Multimedia', 'Student'],
            'is_multiple_input' => true,
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'RAM',
            'input_type' => 'integer',
            'filter_type' => 'fixed_range',
            'postfix' => ' GB',
        ],
        [
            'name' => 'RAM Memory Technology',
            'input_type' => 'string_all_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Processor Brand',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Processor Speed',
            'input_type' => 'numeric',
            'filter_type' => 'range',
            'postfix' => ' GHz',
        ],
        [
            'name' => 'Processor Count',
            'input_type' => 'integer',
            'filter_type' => 'fixed',
            'is_applicable' => false,
            'is_required' => false
        ],
        [
            'name' => 'Maximum RAM Supported',
            'input_type' => 'integer',
            'filter_type' => 'fixed',
            'postfix' => ' GB',
            'is_applicable' => false,
            'is_required' => false
        ],
        [
            'name' => 'Operating System',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Storage Type',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Storage Size',
            'input_type' => 'integer',
            'filter_type' => 'range',
            'postfix' => ' GB',
        ],
        [
            'name' => 'Graphics Card',
            'input_type' => 'string_first_cap',
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Brightness',
            'input_type' => 'integer',
            'filter_type' => 'range',
            'postfix' => 'Nits',
            'is_required' => false,
        ],
        [
            'name' => 'Display Size',
            'input_type' => 'numeric',
            'filter_type' => 'range',
            'postfix' => ' inch',
        ],
        [
            'name' => 'Display Resoulution',
            'input_type' => 'string',
            'filter_type' => 'fixed',
            'postfix' => ' Pixels',
        ],
        [
            'name' => 'Biometric Security',
            'input_type' => 'string_first_cap',
            'is_multiple_input' => true,
            'filter_type' => 'fixed',
        ],
        [
            'name' => 'Laptop Graphics Memory',
            'input_type' => 'integer',
            'filter_type' => 'fixed_range',
            'postfix' => ' GB',
            'is_required' => false,
        ],
        [
            'name'=> 'Laptop Battery Life',
            'input_type' => 'numeric',
            'filter_type' => 'fixed_range',
            'postfix' => 'h',
        ],
        [
            'name' => 'Laptop Weight',
            'input_type' => 'numeric',
            'filter_type' => 'fixed_range',
            'postfix' => ' kg',
        ]

    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        return self::variationStructData;
    }
}
