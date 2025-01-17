<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpentController;
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
GET /spents/create → SpentController@create
POST /spents → SpentController@store
GET /spents/{product} → SpentController@show
GET /spents/{product}/edit → SpentController@edit
PUT/PATCH /spents/{product} → SpentController@update
DELETE /spents/{product} → SpentController@destroy
*/

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [SpentController::class, 'index'])->name('dashboard');
    Route::resource('spents', SpentController::class);
    Route::resource('configuration', ConfigurationController::class);
    Route::get('/get-info', [ConfigurationController::class, 'getInfo']);
    Route::resource('fixedexpenses', FixedExpenseController::class);
});