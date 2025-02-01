<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', [LoginController::class, 'login']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'login']);
Route::post('/reset-password', [ResetPasswordController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // ! GLOBAL REQUEST
    Route::get('/logout', [LoginController::class, 'logout'])->middleware('web');
    Route::get('/user-info', [LoginController::class, 'getUserInfo']);

    Route::group(['prefix' => 'gis/dashboard'], function () {
        // ! GIS DASHBOARD


        // ! GIS FEATURE
        Route::group(['prefix' => '/data-bidang'], function () {
            
        });

        Route::group(['prefix' => '/geojson-data'], function () {
            
        });
    });
});