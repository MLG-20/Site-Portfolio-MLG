<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Experience;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tes infos personnelles
        PersonalInfo::create([
            'name' => 'Mamadou Lamine Gueye',
            'titles' => json_encode(['Développeur Full-Stack', 'Data Analyst', 'Expert Power BI']),
            'email' => 'contact@laminegueye.dev',
            'phone' => '+221 77 123 45 67',
            'linkedin' => 'https://linkedin.com',
            'github' => 'https://github.com',
            'cv_path' => 'files/cv.pdf',
            'profile_image' => 'images/photo_profil.jpg' // Assure-toi d'avoir une image ici plus tard
        ]);

        // 2. Une expérience de test
        Experience::create([
            'title' => 'Étudiant Licence 3 MIAGE',
            'company' => 'Université Iba Der Thiam',
            'duration' => '2023 - Présent',
            'description' => 'Spécialisation en Management Informatisé des Organisations et Data Analysis.',
            'icon' => 'fa-solid fa-graduation-cap'
        ]);

        // 3. Un projet de test
        Project::create([
            'title' => 'Portfolio Laravel',
            'description' => 'Mon portfolio personnel développé avec Laravel 10 et MySQL.',
            'image' => 'images/projet_demo.jpg',
            'tags' => json_encode(['Laravel', 'PHP', 'MySQL']),
            'demo_link' => '#',
            'github_link' => '#'
        ]);
    }
}