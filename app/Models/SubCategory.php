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
        // return $this->hasMany(ConnectFilterSubCategory::class, 'sub_category_id')->join('form_input_structures', 'connect_filter_sub_categories.filter_structure', '=', 'form_input_structures.form_input_structure_id')->select(['form_input_structures.*', 'connect_filter_sub_categories.name', 'connect_filter_sub_categories.is_applicable', 'connect_filter_sub_categories.sub_category_id']);
        return $this->hasMany(ConnectFilterSubCategory::class, 'sub_category_id')->with('filterStructure');
        // return $this->belongsToMany('App\Models\FormInputStructure', 'connect_filter_sub_categories', 'sub_category_id', 'filter_structure');
    }

    public function variationStructure()
    {
        return $this->hasOne('App\Models\FormInputStructure', 'form_input_structure_id', 'variation_structure')
        ->select(['form_input_structure_id', 'name', 'input_type', 'filter_type', 'suffix',]);
    }

    public function subVariationStructure()
    {
        return $this->hasOne('App\Models\FormInputStructure', 'form_input_structure_id', 'sub_variation_structure')
        ->select(['form_input_structure_id', 'name', 'input_type', 'input_list', 'filter_type', 'suffix',]);
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
