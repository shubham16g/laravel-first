<?php

namespace App\Models\Structure;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationStructure extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'variation_structure_id';
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

    // static function to create new VariationStructure
    public static function store(string $name, string $input_type, ?string $extras, ?string $filter_type, ?string $postfix, int $id = 0):VariationStructure
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
    }

}
