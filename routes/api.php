<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'getAllUserForApi']);
    Route::post('/', [UserController::class, 'storeUserForApi']);
});
