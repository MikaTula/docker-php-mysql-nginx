<?php

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::controller(RegisterController::class)->group(function(){
    Route::post('custom-register', 'register')->name('custom-register');
    Route::post('custom-login', 'login')->name('custom-login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::resource('songs', SongController::class);


    Route::get('user', function (Request $request) {
        return $request->user();
    })->name('user');
});
