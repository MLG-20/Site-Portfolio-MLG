<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PersonalInfo;
use App\Models\Project;
use App\Models\Experience;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@portfolio.com')],
            [
                'name'     => 'Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        // Infos personnelles (ne crée que si vide)
        if (PersonalInfo::count() === 0) {
            PersonalInfo::create([
                'name'          => 'Mamadou Lamine Gueye',
                'titles'        => json_encode(['Développeur Full-Stack', 'Data Analyst', 'Expert Power BI']),
                'email'         => 'contact@laminegueye.dev',
                'phone'         => '+221 77 123 45 67',
                'linkedin'      => 'https://linkedin.com',
                'github'        => 'https://github.com',
                'cv_path'       => 'files/cv.pdf',
                'profile_image' => 'images/photo_profil.jpg',
            ]);
        }

        // Expériences (ne crée que si vide)
        if (Experience::count() === 0) {
            Experience::create([
                'title'       => 'Étudiant Licence 3 MIAGE',
                'company'     => 'Université Iba Der Thiam',
                'duration'    => '2023 - Présent',
                'description' => 'Spécialisation en Management Informatisé des Organisations et Data Analysis.',
                'icon'        => 'fa-solid fa-graduation-cap',
            ]);
        }

        // Projets (ne crée que si vide)
        if (Project::count() === 0) {
            Project::create([
                'title'       => 'Portfolio Laravel',
                'description' => 'Mon portfolio personnel développé avec Laravel et MySQL.',
                'image'       => 'images/projet_demo.jpg',
                'tags'        => json_encode(['Laravel', 'PHP', 'MySQL']),
                'demo_link'   => '#',
                'github_link' => '#',
            ]);
        }
    }
}
