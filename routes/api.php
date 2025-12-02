<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::post('/auth/login', [AuthController::class, 'apiLogin']);
Route::post('/auth/register', [AuthController::class, 'apiRegister']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'apiUser']);
});





