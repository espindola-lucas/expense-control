<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\SessionAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpentController;
use App\Http\Controllers\PersonalConfigurationController;
use App\Http\Controllers\BusinessConfigurationController;
use App\Http\Controllers\ConfigurationHomeController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FixedExpenseController;
// use App\Http\Middleware\CheckSessionTimeout;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('session.login');
})->name('login');

Route::get('/register', function () {
    return view('session.register');
})->name('session-register');

Route::post('/register', [SessionAuthController::class, 'register'])->name('session-register.store');
Route::post('/login', [SessionAuthController::class, 'login'])->name('login.authenticate');
Route::post('/logout', [SessionAuthController::class, 'logout'])->name('session-logout');
Route::get('/verify-email/{id}/{hash}/', [SessionAuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('/forgot-password', [SessionAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [SessionAuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [SessionAuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [SessionAuthController::class, 'resetPassword'])->name('password.update');

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
    'verified',
    // CheckSessionTimeout::class,
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('spents', SpentController::class);
    Route::resource('sells', SellController::class);
    Route::resource('configuration', ConfigurationHomeController::class);
    Route::resource('personal-configuration', PersonalConfigurationController::class);
    Route::resource('business-configuration', BusinessConfigurationController::class);
    Route::get('/get-info', [PersonalConfigurationController::class, 'getInfo']);
    Route::resource('fixedexpenses', FixedExpenseController::class);
    Route::resource('is_admin', AdminController::class)
        ->parameters(['is_admin' => 'user'])
        ->only(['index', 'destroy']);
});