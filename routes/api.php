<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\ContactController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('annonces', [AnnonceController::class, 'index']);
    Route::post('annonces', [AnnonceController::class, 'store']);
    Route::delete('annonces/{id}', [AnnonceController::class, 'destroy']);
    Route::get('/liste-users', [AuthController::class, 'usersList']);
    Route::post('/send-mail', [ContactController::class, 'sendMail']);
});

Route::get('annonces/list', [AnnonceController::class,'index']);
Route::get('annonces/{id}', [AnnonceController::class,'show']);