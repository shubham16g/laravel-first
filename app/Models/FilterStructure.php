<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterStructure extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'filter_structure_id';
    public $timestamps = false;

    protected $hidden = [
        'pivot',
        'filter_structure_id',
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
