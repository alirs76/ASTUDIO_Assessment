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

	Route::prefix('users')
		->name('users.')
		->controller(\App\Http\Controllers\Api\UserController::class)
		->group(function () {
			Route::get('/', 'list')->name('list');
			Route::get('/{user}', 'show')->name('show');
			Route::put('/{user}', 'update')->name('update');
			Route::delete('/{user}', 'delete')->name('delete');
		});
});
