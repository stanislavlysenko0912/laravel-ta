<?php

use App\Http\Controllers\V1\Position\PositionController;
use App\Http\Controllers\V1\Token\TokenController;
use App\Http\Controllers\V1\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/token', TokenController::class);
Route::get('/positions', PositionController::class);

Route::resource('users', UserController::class)->only(['index', 'show']);
Route::resource('users', UserController::class)->only(['store'])->middleware(['jwt', 'auth:web']);