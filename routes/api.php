<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/info', [UserController::class,'infoShow']);
    Route::put('/info', [UserController::class,'infoUpdate']);
    Route::delete('/token', [UserController::class,'tokenDelete']);
});

Route::post('/signup', [UserController::class, 'signUp']);

Route::post('/signin', [UserController::class, 'signIn']);

Route::get('/latency', [UserController::class, 'latency']);
