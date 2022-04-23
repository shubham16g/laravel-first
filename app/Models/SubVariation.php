<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubVariation extends Model
{
    use HasFactory;
    // primarykey
    protected $primaryKey = 'sub_variation_id';

    // protected $visible = ['price', 'mrp'];
}
