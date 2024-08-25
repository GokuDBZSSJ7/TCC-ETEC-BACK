<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'store']);


Route::get('getPoliticians', [UserController::class, 'getPoliticians']);
Route::get('me', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::resource('users', UserController::class);
Route::middleware('auth:sanctum')->group(function () {});