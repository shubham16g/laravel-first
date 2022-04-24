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

}
