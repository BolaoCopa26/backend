<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/games', [GameController::class, 'index']);
    Route::post('/predictions', [PredictionController::class, 'store']);
    Route::get('/ranking', [\App\Http\Controllers\RankingController::class, 'index']);
    
    // Admin route to set match result
    Route::post('/admin/games/{id}/result', [\App\Http\Controllers\AdminController::class, 'setGameResult']);
});
