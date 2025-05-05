<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Http\Requests\StoreStoryRequest;
use App\Http\Requests\UpdateStoryRequest;
use Illuminate\Http\JsonResponse;

class StoryController extends Controller
{
    public function indexStory(): JsonResponse
    {
        $stories = Story::all()->map(function ($story) {
            return [
                'id' => $story->id,
                'title' => $story->title,
                'summary' => $story->summary,
                'author' => $story->author,
                'cover' => $story->cover,
                'playable' => $story->summary !== null && $story->summary !== ''
            ];
        });

        return response()->json($stories);
    }

    public function showStory(Story $story): JsonResponse
    {
        // Charger les chapitres avec leurs choix pour un rendu optimal
        $story->load(['chapters' => function($query) {
            $query->orderBy('chapter_number');
        }, 'chapters.choices']);
        
        return response()->json($story);
        }

    public function createNewStory(StoreStoryRequest $request): JsonResponse
    {
        $story = Story::create($request->validated());
        return response()->json($story, 201);
    }

    public function updateStory(UpdateStoryRequest $request, Story $story): JsonResponse
    {
        $story->update($request->validated());
        return response()->json($story);
    }

    public function destroyStory(Story $story): JsonResponse
    {
        $story->delete();
        return response()->json(['message' => 'Story deleted successfully']);
    }
}
