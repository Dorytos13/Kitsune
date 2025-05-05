<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class GameInfoController extends Controller
{
    /**
     * Récupérer les informations sur le jeu
     * Cette route est protégée et nécessite une authentification
     */
    public function getGameInfo(): JsonResponse
    {
        return response()->json([
            'title' => 'Kitsune: Légendes Japonaises',
            'description' => 'Une aventure interactive où vous incarnez un photographe à la recherche d\'un mystérieux kitsune dans les forêts du Japon.',
            'author' => 'Votre nom',
            'version' => '1.0.0',
            'releaseDate' => '2025-05-01',
            'howToPlay' => [
                'Lisez attentivement chaque passage de l\'histoire',
                'Faites vos choix en fonction de ce que vous pensez être la meilleure action',
                'Votre progression est automatiquement sauvegardée si vous êtes connecté',
                'Certains choix peuvent mener à une fin prématurée, alors choisissez avec sagesse!'
            ],
            'about' => 'Ce jeu a été développé dans le cadre d\'un projet académique pour explorer les légendes autour des kitsune, ces esprits-renards du folklore japonais. Mêlant photographie et exploration narrative, le jeu vous emmène dans un voyage mystique à travers le Japon rural.',
            'tips' => [
                'Soyez respectueux des traditions locales',
                'La patience est souvent récompensée',
                'Tout ce qui brille n\'est pas or... ou renard!',
                'Prêtez attention aux détails dans l\'histoire, ils peuvent contenir des indices'
            ]
        ]);
    }
}