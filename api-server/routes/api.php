<?php

use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\SingerController;
use App\Http\Controllers\API\SongController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function () {
    Route::post('custom-register', 'register')->name('custom-register');
    Route::post('custom-login', 'login')->name('custom-login');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    })->name('user');

    Route::get('custom-logout', [RegisterController::class, 'logout'])->name('custom-logout');

    Route::apiResource('songs', SongController::class)->only(['index', 'store']);
    Route::get('songs/{song}', [SongController::class, 'show'])
        ->middleware('can:view,song')
        ->name('songs.show');
    Route::match(['put', 'patch'], 'songs/{song}', [SongController::class, 'update'])
        ->middleware('can:update,song')
        ->name('songs.update');
    Route::delete('songs/{song}', [SongController::class, 'destroy'])
        ->middleware('can:delete,song')
        ->name('songs.destroy');

    Route::apiResource('genres', GenreController::class)->only(['index', 'store']);
    Route::get('genres/{genre}', [GenreController::class, 'show'])
        ->middleware('can:view,genre')
        ->name('genres.show');
    Route::match(['put', 'patch'], 'genres/{genre}', [GenreController::class, 'update'])
        ->middleware('can:update,genre')
        ->name('genres.update');
    Route::delete('genres/{genre}', [GenreController::class, 'destroy'])
        ->middleware('can:delete,genre')
        ->name('genres.destroy');

    Route::apiResource('singers', SingerController::class)->only(['index', 'store']);
    Route::get('singers/{singer}', [SingerController::class, 'show'])
        ->middleware('can:view,singer')
        ->name('singers.show');
    Route::match(['put', 'patch'], 'singers/{singer}', [SingerController::class, 'update'])
        ->middleware('can:update,singer')
        ->name('singers.update');
    Route::delete('singers/{singer}', [SingerController::class, 'destroy'])
        ->middleware('can:delete,singer')
        ->name('singers.destroy');

    Route::apiResource('files', FileController::class)->only(['index', 'store']);
    Route::get('files/download/{id}', [FileController::class, 'download'])
        ->middleware('can:view,file');
    Route::get('files/{file}', [FileController::class, 'show'])
        ->middleware('can:view,file')
        ->name('files.show');
    Route::match(['put', 'patch'], 'files/{file}', [FileController::class, 'update'])
        ->middleware('can:update,file')
        ->name('files.update');
    Route::delete('files/{file}', [FileController::class, 'destroy'])
        ->middleware('can:delete,file')
        ->name('files.destroy');

});
