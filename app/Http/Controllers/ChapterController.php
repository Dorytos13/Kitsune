<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use Illuminate\Http\JsonResponse;

class ChapterController extends Controller
{
    /**
     * Afficher la liste des chapitres
     */
    public function getChapters(): JsonResponse
    {
        $chapters = Chapter::with(['story', 'choices'])->get();
        return response()->json($chapters);
    }

    /**
     * Afficher un chapitre spécifique
     */
    public function getChapter(Chapter $chapter): JsonResponse
    {
        $chapter->load(['story', 'choices']);
        
        return response()->json([
            'chapter' => $chapter,
            'has_choices' => $chapter->choices->isNotEmpty(),
        ]);
    }

    /**
     * Créer un nouveau chapitre
     */
    public function createNewChapter(StoreChapterRequest $request): JsonResponse
    {
        $chapter = Chapter::create($request->validated());
        return response()->json($chapter, 201);
    }

    /**
     * Mettre à jour un chapitre existant
     */
    public function updateChapter(UpdateChapterRequest $request, Chapter $chapter): JsonResponse
    {
        $chapter->update($request->validated());
        return response()->json($chapter);
    }

    /**
     * Supprimer un chapitre
     */
    public function destroyChapter(Chapter $chapter): JsonResponse
    {
        $chapter->delete();
        return response()->json(['message' => 'Chapter deleted successfully']);
    }
}
