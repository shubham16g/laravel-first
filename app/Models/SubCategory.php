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
        return $this->belongsToMany('App\Models\Structure\FilterStructure', 'connect_filter_sub_categories', 'sub_category_id', 'filter_structure_id');
    }

    public function variationStructure()
    {
        return $this->hasOne('App\Models\Structure\VariationStructure', 'variation_structure_id', 'variation_structure')
        ->select(['variation_structure_id', 'name', 'input_type', 'filter_type', 'postfix',]);
    }

    public function subVariationStructure()
    {
        return $this->hasOne('App\Models\Structure\SubVariationStructure', 'sub_variation_structure_id', 'sub_variation_structure')
        ->select(['sub_variation_structure_id', 'name', 'input_type', 'input_list', 'filter_type', 'postfix',]);
    }

    public function setTypeListAttribute($value)
    {
        if ($value != null)
            $this->attributes['type_list'] = json_encode($value);
    }

    public function getTypeListAttribute($value)
    {
        if ($value == null)
            return null;
        return json_decode($value);
    }
}
