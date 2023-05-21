<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function (\Illuminate\Http\Request $request) {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home')
    ->middleware(['auth', '2fa']);

Route::get('/input-pin-code', function () {
    return view('auth/pincode');
})->name('auth.input.pinCode')->middleware(['guest']);

Route::get('2fa', [App\Http\Controllers\TwoFAController::class, 'index'])
    ->name('2fa.index')
    ->middleware(['auth', 'require.2fa.code']);

Route::post('2fa', [App\Http\Controllers\TwoFAController::class, 'store'])
    ->name('2fa.post')
    ->middleware(['auth']);
