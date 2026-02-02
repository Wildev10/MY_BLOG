<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Laravel', 'slug' => 'laravel', 'color' => '#FF2D20'],
            ['name' => 'Vue.js', 'slug' => 'vuejs', 'color' => '#42B883'],
            ['name' => 'JavaScript', 'slug' => 'javascript', 'color' => '#F7DF1E'],
            ['name' => 'PHP', 'slug' => 'php', 'color' => '#777BB4'],
            ['name' => 'Python', 'slug' => 'python', 'color' => '#3776AB'],
            ['name' => 'React', 'slug' => 'react', 'color' => '#61DAFB'],
            ['name' => 'Node.js', 'slug' => 'nodejs', 'color' => '#339933'],
            ['name' => 'TypeScript', 'slug' => 'typescript', 'color' => '#3178C6'],
            ['name' => 'CSS', 'slug' => 'css', 'color' => '#1572B6'],
            ['name' => 'HTML', 'slug' => 'html', 'color' => '#E34F26'],
            ['name' => 'Tailwind', 'slug' => 'tailwind', 'color' => '#06B6D4'],
            ['name' => 'Docker', 'slug' => 'docker', 'color' => '#2496ED'],
            ['name' => 'Git', 'slug' => 'git', 'color' => '#F05032'],
            ['name' => 'MySQL', 'slug' => 'mysql', 'color' => '#4479A1'],
            ['name' => 'API', 'slug' => 'api', 'color' => '#6366F1'],
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'color' => '#10B981'],
            ['name' => 'Tips', 'slug' => 'tips', 'color' => '#F59E0B'],
            ['name' => 'Débutant', 'slug' => 'debutant', 'color' => '#8B5CF6'],
            ['name' => 'Avancé', 'slug' => 'avance', 'color' => '#EF4444'],
            ['name' => 'DevOps', 'slug' => 'devops', 'color' => '#EC4899'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['slug' => $tag['slug']], $tag);
        }

        $this->command->info('Tags créés avec succès !');
    }
}
