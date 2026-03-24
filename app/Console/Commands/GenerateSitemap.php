<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature   = 'sitemap:generate';
    protected $description = 'Génère le fichier sitemap.xml dans le dossier public';

    public function handle(): void
    {
        $sitemap = Sitemap::create();

        // Page d'accueil
        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        );

        // Pages de projets
        Project::all()->each(function (Project $project) use ($sitemap) {
            $sitemap->add(
                Url::create(route('projects.show', $project))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setLastModificationDate($project->updated_at)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap généré : ' . public_path('sitemap.xml'));
    }
}
