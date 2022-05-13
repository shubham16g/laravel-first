<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectFilterSubCategory extends Model
{
    use HasFactory;

    // notimestamp
    public $timestamps = false;

    // fillable
    protected $fillable = [
        'sub_category_id',
        'filter_structure',
        'name',
        'is_applicable',
    ];

    protected $casts = [
        'is_applicable' => 'boolean',
    ];

    protected $hidden = [
        'pivot',
        'id',
        'sub_category_id',
    ];

    public function filterStructure()
    {
        return $this->belongsTo('App\Models\FormInputStructure', 'filter_structure');
    }
}
