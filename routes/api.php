<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// Public routes
Route::controller(\App\Http\Controllers\Api\AuthController::class)
	->group(function () {
		Route::post('/register', 'register');
		Route::post('/login', 'login');
	});

// Protected routes
Route::middleware('auth:api')->group(function () {
	Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
});
