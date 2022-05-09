<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'image'
    ];

    // primary key
    protected $primaryKey = 'base_category_id';
    // disable timestamp
    public $timestamps = false;

    public function categories()
    {
        return $this->hasMany(Category::class, 'base_category_id');
    }


    public static function store(string $name, string $icon, string $image): BaseCategory
    {
        $baseCategory = new BaseCategory();
        $baseCategory->name = $name;
        $baseCategory->icon = $icon;
        $baseCategory->image = $image;
        $baseCategory->save();
        return $baseCategory;
    }
}
