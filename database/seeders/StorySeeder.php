<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\Choice;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $json = File::get(database_path('seeders/story.json'));
        $storyData = json_decode($json, true);

        $story = Story::create([
            'title' => $storyData['title'],
            'summary' => $storyData['summary'],
            'author' => $storyData['author'],
            'cover' => $storyData['cover'] ?? null, 
        ]);

        $chapterIdMap = [];

        // Première passe: créer tous les chapitres
        foreach ($storyData['chapters'] as $chapter) {
            $newChapter = Chapter::create([
                'story_id' => $story->id,
                'chapter_number' => $chapter['chapter_number'],
                'content' => $chapter['content'],
                'image' => $chapter['image'] ?? null,
            ]);

            $chapterIdMap[$chapter['chapter_number']] = $newChapter->id;
        }

        // Deuxième passe: créer les choix avec vérification
        foreach ($storyData['chapters'] as $chapter) {
            $currentChapterId = $chapterIdMap[$chapter['chapter_number']];

            // Vérifier si le chapitre a des choix et si le tableau n'est pas vide
            if (isset($chapter['choices']) && is_array($chapter['choices']) && count($chapter['choices']) > 0) {
                foreach ($chapter['choices'] as $choice) {
                    Choice::create([
                        'chapter_id' => $currentChapterId,
                        'text' => $choice['text'],
                        'next_chapter_id' => $chapterIdMap[$choice['next_chapter']],
                    ]);
                }
            }
        }
    }
}