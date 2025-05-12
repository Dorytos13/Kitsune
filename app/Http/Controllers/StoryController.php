<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use Illuminate\Http\JsonResponse;

class StoryController extends Controller
{
    /**
     * Liste toutes les histoires
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        try {
            $stories = Story::all()->map(function ($story) {
                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'summary' => $story->summary,
                    'author' => $story->author,
                    'cover' => $story->cover,
                    'playable' => !empty($story->summary),
                ];
            });

            return response()->json($stories, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des histoires.',
                'details' => $e->getMessage()
            ], 500); 
        }
    }

    /**
     * Affiche une histoire spécifique
     * @param Story $story
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(Story $story): JsonResponse
    {
        try {
            $story->load(['chapters' => function ($query) {
                $query->orderBy('chapter_number');
            }, 'chapters.choices']);

            return response()->json($story, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Histoire introuvable.',
                'details' => $e->getMessage()
            ], 404); 
        }
    }

    /**
     * Met à jour une histoire existante
     * @param UpdateStoryRequest $request
     * @param Story $story
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(UpdateStoryRequest $request, Story $story): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $story->update($validatedData);

            return response()->json($story, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de l\'histoire.',
                'details' => $e->getMessage()
            ], 422); 
        }
    }

    /**
     * Supprime une histoire
     * @param Story $story
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Story $story): JsonResponse
    {
        try {
            $story->delete();

            return response()->json(['message' => 'Histoire supprimée avec succès.'], 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de l\'histoire.',
                'details' => $e->getMessage()
            ], 500); 
        }
    }
}