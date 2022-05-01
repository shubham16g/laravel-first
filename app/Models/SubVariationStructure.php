<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubVariationStructure extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'sub_variation_structure_id';
    public $timestamps = false;

    protected $hidden = [
        'pivot',
    ];

    public function setInputListAttribute($value)
    {
        if ($value != null)
            $this->attributes['input_list'] = json_encode($value);
    }

    public function getInputListAttribute($value)
    {
        if ($value == null)
            return null;
        return json_decode($value);
    }

    // static function to create new SubVariationStructure
    public static function store(string $name, string $input_type, ?array $input_list, ?string $extras, ?string $filter_type, ?string $postfix):SubVariationStructure
    {
        $filter = new SubVariationStructure();
        $filter->name = $name;
        $filter->input_type = $input_type;
        $filter->input_list = $input_list;
        $filter->extras = $extras;
        if ($filter_type != null && ($input_type == 'numreic' || $input_type == 'integer'))
        $filter->filter_type = $filter_type;
        $filter->postfix = $postfix;
        $filter->save();
        return $filter;
    }

}
