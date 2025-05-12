<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use Illuminate\Http\JsonResponse;

class ChoiceController extends Controller
{
    /**
     * Liste tous les choix
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(): JsonResponse
    {
        $choices = Choice::with(['chapter', 'nextChapter'])->get();
        return response()->json($choices, 200);
    }
    
    /**
     * Crée un nouveau choix
     * @param StoreChoiceRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreChoiceRequest $request): JsonResponse
    {
        $choice = Choice::create($request->validated());
        return response()->json($choice, 201);
    }
    
    /**
     * Affiche un choix spécifique
     * @param Choice $choice
     * @return JsonResponse
     * @throws \Exception
     */
    public function show(Choice $choice): JsonResponse
    {
        try {
            $choice->load(['chapter', 'nextChapter']);

            return response()->json($choice, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Choix introuvable.'
            ], 404); 
        }
    }
    
    /**
     * Met à jour un choix existant
     * @param UpdateChoiceRequest $request
     * @param Choice $choice
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(UpdateChoiceRequest $request, Choice $choice): JsonResponse
    {
        try {
            $validatedData = $request->validated();

            $choice->update($validatedData);

            return response()->json($choice, 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour du choix.'
            ], 422); 
        }
    }
    
    /**
     * Supprime un choix
     * @param Choice $choice
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Choice $choice): JsonResponse
    {
        try {
            $choice->delete();

            return response()->json(['message' => 'Choix supprimé avec succès.'], 200); 
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression du choix.'
            ], 500); 
        }
    }
}
