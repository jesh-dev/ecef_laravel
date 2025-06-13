<?php

use App\Http\Controllers\memberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [memberController::class, 'register']);
Route::post('/login', [memberController::class, 'login'])->middleware('guest', 'throttle:5,1'); // 5 attempts per minute
Route::post('/verify', [memberController::class, 'verify']);


Route::post('/contact', [memberController::class, 'sendMail']);
// Route::post('/contact', [memberController::class, 'index'])->name('contact');

