<?php

use App\Http\Controllers\CatController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/stripe/webhook', [StripeWebhookController::class,'webhook']);
Route::post('/auth/login', [AuthController::class, 'apiLogin']);
Route::post('/auth/register', [AuthController::class, 'apiRegister']);
Route::post('/stripe/subscription/completed', [PackageController::class, 'completedSubscription']);
Route::post('/stripe/subscription/update', [PackageController::class, 'updateSubscription']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'apiUser']);
    Route::get('/user/stripe/payment/detail', [AuthController::class, 'getStripeDetails']);
    Route::post('/change-password', [AuthController::class, 'apiChangePassword']);

});

Route::prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'apiIndex']);
});

Route::prefix('hba')->group(function () {
    Route::get('/packages', [PackageController::class, 'getPackages']);
    Route::get('/cats', [CatController::class, 'allCats']);
    Route::get('/cats-with-subcats', [CatController::class, 'allCatsWithSubcats']);
    Route::get('/cat/{cat_slug}', [CatController::class, 'getCatBySlug']);
    Route::get('/cat-with-subcats/{cat_slug}', [CatController::class, 'getCatWithSubcats']);
    Route::get('/subcats', [CatController::class, 'allSubcats']);
    Route::get('/subcat/{subcat_slug}', [CatController::class, 'getSubcatBySlug']);
});





