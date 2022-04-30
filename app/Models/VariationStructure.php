<?php

namespace App\Models;

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

}
