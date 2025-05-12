<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use Illuminate\Http\JsonResponse;

class ChapterController extends Controller
{
    /**
     * Liste tous les chapitres
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $chapters = Chapter::with(['story', 'choices'])->get();
        return response()->json($chapters, 200);
    }

    /**
     * Crée un nouveau chapitre
     * @param StoreChapterRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreChapterRequest $request): JsonResponse
    {
        $chapter = Chapter::create($request->validated());
        return response()->json($chapter, 201);
    }

    /**
     * Affiche un chapitre spécifique
     * @param Chapter $chapter
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(Chapter $chapter): JsonResponse
    {
        try {
            $chapter->load(['story', 'choices']);

            return response()->json($chapter, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Chapitre introuvable.'
            ], 404); 
        }

    }

    /**
     * Met à jour un chapitre existant
     * @param UpdateChapterRequest $request
     * @param Chapter $chapter
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(UpdateChapterRequest $request, Chapter $chapter): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $chapter->update($validatedData);
            return response()->json($chapter, 200); // 200 OK
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour du chapitre.'
            ], 422);
        }
    }

    /**
     * Supprime un chapitre
     * @param Chapter $chapter
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Chapter $chapter): JsonResponse
    {
        try {
            $chapter->delete();

            return response()->json(['message' => 'Chapitre supprimé avec succès.'], 200); // 200 OK
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression du chapitre.'
            ], 500);
        }
    }   
}
