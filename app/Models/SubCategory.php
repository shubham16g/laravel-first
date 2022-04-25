<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'sub_category_id';
    // disable timestamps
    public $timestamps = false;

    public function filterStructues()
    {
        return $this->belongsToMany('App\Models\FilterStructure', 'connect_filter_sub_categories', 'sub_category_id', 'filter_structure_id');
    }

    public function setTypeValuesAttribute($value)
    {
        if ($value != null)
            $this->attributes['type_values'] = json_encode($value);
    }

    public function setVariationInputListAttribute($value)
    {
        if ($value != null)
            $this->attributes['variation_input_list'] = json_encode($value);
    }

    public function setSubVariationInputListAttribute($value)
    {
        if ($value != null)
            $this->attributes['sub_variation_input_list'] = json_encode($value);
    }

    public function getTypeValuesAttribute($value)
    {
        if ($value == null)
            return null;
        return json_decode($value);
    }

    public function getVariationInputListAttribute($value)
    {
        if ($value == null)
            return null;
        return json_decode($value);
    }

    public function getSubVariationInputListAttribute($value)
    {
        if ($value == null)
            return null;
        return json_decode($value);
    }
}
