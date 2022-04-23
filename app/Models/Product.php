<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'product_id';

    public function allTags()
    {
        return $this->belongsToMany('App\Models\AllTag', 'connects_all_tags', 'product_id', 'all_tag_id');
    }

    public function subVariations()
    {
        return $this->hasMany('App\Models\SubVariation', 'product_id')
        ->select(['product_id', 'sub_variation_id', 'sub_variation', 'price', 'mrp', 'status']);
    }
}
