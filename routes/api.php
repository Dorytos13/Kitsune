<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameInfoController;

Route::prefix('v1')->group(function () {
    // Routes d'authentification (publiques)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/stories', [StoryController::class, 'index']);
    Route::get('/stories/{story}', [StoryController::class, 'show']);
    Route::get('/chapters/{chapter}', [ChapterController::class, 'show']);
    Route::get('/choices/{choice}', [ChoiceController::class, 'show']);


    // Routes publiques
    Route::get('/about', [GameInfoController::class, 'getGameInfo']);

    // Routes protégées (nécessitent authentification)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        // Routes CRUD pour les ressources
        Route::apiResource('stories', StoryController::class)->except(['index', 'show']);
        Route::apiResource('chapters', ChapterController::class)->except(['index', 'show']);
        Route::apiResource('choices', ChoiceController::class)->except(['index', 'show']);

    });
});