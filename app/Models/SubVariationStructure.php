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

}
