<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConnectSubCategory extends Model
{
    use HasFactory;

    // disable timestamp
    public $timestamps = false;

    protected $fillable = [
        'sub_category_id', 'category_id', 'type'];
}
    