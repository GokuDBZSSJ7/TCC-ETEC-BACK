<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PromisseController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('register', [UserController::class, 'store']);


Route::get('getPoliticians', [UserController::class, 'getPoliticians']);
Route::get('getUsers', [UserController::class, 'getUsers']);
Route::get('me', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::resource('users', UserController::class);
Route::resource('areas', AreaController::class);
Route::middleware('auth:sanctum')->group(function () {});

Route::resource('states', StateController::class);
Route::resource('cities', CityController::class);
Route::get('getCitieByStateId/{id}', [CityController::class, 'getCitieByStateId']);

Route::resource('parties', PartyController::class);

Route::post('filterPoliticians', [UserController::class, 'filterPoliticians']);

Route::post('upgradeToCandidate', [UserController::class, 'upgradeToCandidate']);

Route::put('updateUser/{id}', [UserController::class, 'update']);

Route::resource('proposals', PromisseController::class);