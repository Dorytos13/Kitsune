<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameInfoController extends Controller
{
    /**
     * Récupérer les informations sur le jeu
     * Cette route est protégée et nécessite une authentification
     */
    public function getGameInfo(Request $request): JsonResponse
    {
        try {
            // Vérifie si l'utilisateur est authentifié
            if (!$request->user()) {
                return response()->json([
                    'error' => 'Utilisateur non authentifié.'
                ], 401); 
            }

            // Retourne les informations sur le jeu
            return response()->json([
                'title' => 'Kitsune: Légendes Japonaises',
                'description' => 'Une aventure interactive où vous incarnez un photographe à la recherche d\'un mystérieux kitsune dans les forêts du Japon.',
                'author' => 'Doriane',
                'version' => '1.0.0',
                'releaseDate' => '2025-05-01',
                'howToPlay' => [
                    'Lisez attentivement chaque passage de l\'histoire',
                    'Faites vos choix en fonction de ce que vous pensez être la meilleure action',
                    'Votre progression est automatiquement sauvegardée dans votre LocalStorage sans même avoir besoin de connexion'
                ],
                'about' => 'Ce jeu a été développé dans le cadre d\'un projet commun entre deux cours de mon bachelor en Ingénierie des Médias.',
            ], 200); 
        } catch (\Exception $e) {
            // Gestion des erreurs inattendues
            return response()->json([
                'error' => 'Une erreur est survenue lors de la récupération des informations du jeu.',
                'details' => $e->getMessage()
            ], 500); 
        }
    }
}