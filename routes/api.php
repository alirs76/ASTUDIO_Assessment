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

	Route::prefix('projects')
		->name('projects.')
		->controller(\App\Http\Controllers\Api\ProjectController::class)
		->group(function () {
			Route::get('/', 'list')->name('list');
			Route::get('/{project}', 'show')->name('show');
			Route::post('/', 'create')->name('create');
			Route::put('/{project}', 'update')->name('update');
			Route::delete('/{project}', 'delete')->name('delete');
		});

	Route::prefix('timesheets')
		->name('timesheets.')
		->controller(\App\Http\Controllers\Api\TimesheetController::class)
		->group(function () {
			Route::get('/', 'list')->name('list');
			Route::get('/{timesheet}', 'show')->name('show');
			Route::post('/', 'create')->name('create');
			Route::put('/{timesheet}', 'update')->name('update');
			Route::delete('/{timesheet}', 'delete')->name('delete');
		});

	Route::prefix('attributes')
		->name('attributes.')
		->controller(\App\Http\Controllers\Api\AttributeController::class)
		->group(function () {
			Route::get('/', 'list')->name('list');
			Route::get('/{attribute}', 'show')->name('show');
			Route::post('/', 'create')->name('create');
			Route::put('/{attribute}', 'update')->name('update');
			Route::delete('/{attribute}', 'delete')->name('delete');
		});
});
