<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllTag extends Model
{
    use HasFactory;

    // primarykey
    public $primaryKey = 'all_tag_id';
    // disable timestamps
    public $timestamps = false;

    protected $fillable = [
        'value',
        'type',
    ];
}
