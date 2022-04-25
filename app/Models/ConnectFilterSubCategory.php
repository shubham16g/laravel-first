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
        'filter_structure_id',
    ];
}
