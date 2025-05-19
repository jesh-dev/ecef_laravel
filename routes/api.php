<?php

use App\Http\Controllers\memberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [memberController::class, 'register']);
Route::post('/login', [memberController::class, 'login']);

