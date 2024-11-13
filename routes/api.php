<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\PromisseController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('auth/login', [AuthController::class, 'login']);
Route::get('me', [AuthController::class, 'me']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::post('register', [UserController::class, 'store']);
Route::get('getPoliticians', [UserController::class, 'getPoliticians']);
Route::get('getUsers', [UserController::class, 'getUsers']);
Route::post('filterPoliticians', [UserController::class, 'filterPoliticians']);
Route::post('upgradeToCandidate', [UserController::class, 'upgradeToCandidate']);
Route::put('updateUser/{id}', [UserController::class, 'update']);
Route::get('showMyPromisses/{id}', [UserController::class, 'showMyPromisses']);
Route::resource('users', UserController::class);

Route::resource('areas', AreaController::class);

Route::resource('states', StateController::class);

Route::resource('cities', CityController::class);
Route::get('getCitieByStateId/{id}', [CityController::class, 'getCitieByStateId']);

Route::resource('parties', PartyController::class);

Route::resource('proposals', PromisseController::class);
Route::get('getFinishedProposals/{id}', [PromisseController::class, 'getFinishedProposals']);
Route::get('getWorkingProposals/{id}', [PromisseController::class, 'getWorkingProposals']);

Route::resource('comments', CommentController::class);
Route::get('getCommentsByPromisseId/{id}', [CommentController::class, 'getCommentsByPromisseId']);

Route::post('password/email', [EmailController::class, 'sendResetLinkEmail']);

Route::post('likeToggle', [PromisseController::class, 'toggle']);

Route::middleware('auth:sanctum')->group(function () {
});
