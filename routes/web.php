<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Mail\NotifikasiPendaftaran;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::prefix('auth')->group(function () {
    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'index')->name('auth.register.index');   
        Route::post('register/create', 'create')->name('auth.register.create');   
    });
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('auth.login.index');   
        Route::post('login/store', 'store')->name('auth.login.store');   
    });

    Route::get('/preview-email', function () {
    return new NotifikasiPendaftaran(
        'budi@example.com', 
        'Budi Santoso', 
        'password123'
    );
});
});

Route::post('/save-coordinate', function (\Illuminate\Http\Request $request) {
    Session::put('user_gps_lat', $request->lat);
    Session::put('user_gps_lng', $request->lng);
    return response()->json(['status' => 'success']);
});