<?php

use App\Http\Controllers\CatController;
use App\Http\Controllers\PackageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::post('/auth/login', [AuthController::class, 'apiLogin']);
Route::post('/auth/register', [AuthController::class, 'apiRegister']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'apiUser']);
    Route::post('/change-password', [AuthController::class, 'apiChangePassword']);
});

Route::prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'apiIndex']);
});

Route::prefix('v1')->group(function () {
    Route::get('/packages', [PackageController::class, 'getPackages']);
    Route::get('/cats', [CatController::class, 'allCats']);
    Route::get('/cats-with-subcats', [CatController::class, 'allCatsWithSubcats']);
    Route::get('/cat/{cat_slug}', [CatController::class, 'getCatBySlug']);
    Route::get('/cat-with-subcats/{cat_slug}', [CatController::class, 'getCatWithSubcats']);
    Route::get('/subcats', [CatController::class, 'allSubcats']);
    Route::get('/subcat/{subcat_slug}', [CatController::class, 'getSubcatBySlug']);
});





