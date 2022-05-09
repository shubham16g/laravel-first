<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon', 'image', 'base_category_id'
    ];

    // hidden
    protected $hidden = [
        'base_category_id'
    ];

    // primary key
    protected $primaryKey = 'category_id';
    // disable timestamp
    public $timestamps = false;



    public static function store(string $name, string $icon, string $image, int $baseCategoryId): Category
    {
        $category = new Category();
        $category->name = $name;
        $category->icon = $icon;
        $category->image = $image;
        $category->base_category_id = $baseCategoryId;
        $category->save();
        return $category;
    }

}
