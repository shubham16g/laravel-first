<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SubVariationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function(){
    return 'API is working';

});

/*  StrucutreController*/
Route::post('/form-input-structure', [StructureController::class, 'addFormInputStructure']);
Route::get('/form-input-structure', [StructureController::class, 'getFormInputStructures']);
// Route::post('/filter-structure/', [StructureController::class, 'addFilterStructure']);
// Route::post('/variation-structure/', [StructureController::class, 'addVariationStructure']);
// Route::post('/sub-variation-structure/', [StructureController::class, 'addSubVariationStructure']);


Route::get('/base-category', [CategoryController::class, 'listBaseCategories']);
Route::post('/base-category', [CategoryController::class, 'addBaseCategory']);

Route::get('{baseCategoryId}/category/', [CategoryController::class, 'listCategories']);
Route::post('/category', [CategoryController::class, 'addCategory']);

Route::post('/category/link-sub-category', [CategoryController::class, 'linkSubCategoryToCategory']);

Route::get('{categoryId}/sub-category', [SubCategoryController::class, 'getSubCategories']);
Route::get('/sub-category-structure/{subCategoryId}', [SubCategoryController::class, 'getSubCategoryStructure']);
Route::post('/sub-category', [SubCategoryController::class, 'addSubCategory']);


Route::post('/sub-category/add-filter-structure', [SubCategoryController::class, 'addFilterToSubCategory']);
Route::post('/sub-category/remove-filter-structure', [SubCategoryController::class, 'removeFilterToSubCategory']);

Route::post('/product', [ProductController::class, 'addProduct']);
Route::post('/sub_variation', [SubVariationController::class, 'addSubVariation']);
Route::delete('/sub_variation', [SubVariationController::class, 'deleteSubVariation']);
Route::put('/sub_variation/price', [SubVariationController::class, 'updateSubVariationPrice']);
Route::put('/sub_variation/name', [SubVariationController::class, 'updateSubVariationName']);
Route::get('/product', [ProductController::class, 'list']);
