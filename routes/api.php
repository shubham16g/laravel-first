<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubVariationController;
use Illuminate\Http\Request;
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

Route::post('/product', [SubVariationController::class, 'addProduct']);
Route::get('/product', [ProductController::class, 'list']);
