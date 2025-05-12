<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User de test
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@exemple.com',
            'password' => bcrypt('Password'),
        ]);
        
        // Ajout duStorySeeder
        $this->call([
            StorySeeder::class,
        ]);
    }
}
