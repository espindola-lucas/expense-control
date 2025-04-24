<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpentController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\FixedExpenseController;
use App\Http\Middleware\CheckSessionTimeout;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('session.login');
})->name('session-login');

Route::get('/register', function () {
    return view('session.register');
})->name('session-register');

Route::post('/register', [SessionAuthController::class, 'register'])->name('session-register.store');
Route::post('/login', [SessionAuthController::class, 'login'])->name('session-login.authenticate');
Route::post('/logout', [SessionAuthController::class, 'logout'])->name('session-logout');

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
    CheckSessionTimeout::class,
])->group(function () {
    Route::get('/dashboard', [SpentController::class, 'index'])->name('dashboard');
    Route::resource('spents', SpentController::class);
    Route::resource('configuration', ConfigurationController::class);
    Route::get('/get-info', [ConfigurationController::class, 'getInfo']);
    Route::resource('fixedexpenses', FixedExpenseController::class);
    Route::resource('is_admin', AdminController::class)
        ->parameters(['is_admin' => 'user'])
        ->only(['index', 'destroy']);
});