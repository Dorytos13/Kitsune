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

    // Routes publiques
    Route::get('/stories', [StoryController::class, 'indexStory']);
    Route::get('/stories/{story}', [StoryController::class, 'showStory']);
    Route::get('/chapters/{chapter}', [ChapterController::class, 'getChapter']);
    Route::get('/choices', [ChoiceController::class, 'getChoices']);

    // Routes protégées (nécessitent authentification)
    Route::middleware('auth:sanctum')->group(function () {
        // Route de déconnexion
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        
        // Page d'information sur le jeu (protégée)
        Route::get('/game-info', [GameInfoController::class, 'getGameInfo']);
        
        // Routes d'administration
        Route::post('/stories', [StoryController::class, 'createNewStory']);
        Route::put('/stories/{story}', [StoryController::class, 'updateStory']);
        Route::delete('/stories/{story}', [StoryController::class, 'destroyStory']);
        
        Route::get('/chapters', [ChapterController::class, 'getChapters']);
        Route::post('/chapters', [ChapterController::class, 'createNewChapter']);
        Route::put('/chapters/{chapter}', [ChapterController::class, 'updateChapter']);
        Route::delete('/chapters/{chapter}', [ChapterController::class, 'destroyChapter']);
        
        Route::get('/choices/{choice}', [ChoiceController::class, 'getChoice']);
        Route::post('/choices', [ChoiceController::class, 'createNewChoice']);
        Route::put('/choices/{choice}', [ChoiceController::class, 'updateChoice']);
        Route::delete('/choices/{choice}', [ChoiceController::class, 'destroyChoice']);
    });
});