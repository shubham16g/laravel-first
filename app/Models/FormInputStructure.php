<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormInputStructure extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'form_input_structure_id';
    public $timestamps = false;

    protected $hidden = [
        'pivot',
    ];

    protected $casts = [
        'input_list' => 'array',
        'is_required' => 'boolean',
        'is_multiple_input' => 'boolean',
    ];

    public function setInputListAttribute($value)
    {
        if ($value != null)
            $this->attributes['input_list'] = json_encode($value);
    }

    /* public static function store(string $name, string $input_type, ?string $extras, ?string $filter_type, ?string $postfix, int $id = 0):VariationStructure
    {
        $filter = new VariationStructure();
        if ($id != 0)
            $filter->variation_structure_id = $id;
        $filter->name = $name;
        $filter->input_type = $input_type;
        $filter->extras = $extras;
        if ($filter_type != null && ($input_type == 'numreic' || $input_type == 'integer'))
            $filter->filter_type = $filter_type;
        $filter->postfix = $postfix;
        $filter->save();
        return $filter;
    } */

}
