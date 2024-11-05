<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\FixedExpenseController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
]);

Route::get('/login', function () {
    return view('session.login');
})->name('session-login');

Route::get('/register', function () {
    return view('session.register');
})->name('session-register');


/*
Route::resource()
GET /products/create → ProductController@create
POST /products → ProductController@store
GET /products/{product} → ProductController@show
GET /products/{product}/edit → ProductController@edit
PUT/PATCH /products/{product} → ProductController@update
DELETE /products/{product} → ProductController@destroy
*/

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [ProductController::class, 'index'])->name('dashboard');
    Route::resource('products', ProductController::class);
    Route::resource('configuration', ConfigurationController::class);
    Route::resource('fixedexpenses', FixedExpenseController::class);
});