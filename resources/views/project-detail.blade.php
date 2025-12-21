<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $project->title }} | Projet de {{ $personalInfo->name }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="project-detail-page">

    <!-- Un header simplifié pour cette page -->
    <header class="header sticky">
        <a href="{{ route('home') }}" class="logo">Retour au Portfolio</a>
    </header>

    <section class="project-detail">
        <div class="project-detail-container">

            <!-- COLONNE DE GAUCHE : VISUELS ET INFOS CLÉS -->
            <div class="project-header">
                <h1>{{ $project->title }}</h1>
                <div class="tags-container">
                    @php
                    // On force la transformation en tableau, quoi qu'il arrive
                     $tags = is_string($project->tags) ? explode(',', $project->tags) : ($project->tags ?? []);
                    @endphp

                    @foreach($tags as $tag)
                        <span class="tag">{{ trim($tag) }}</span>
                    @endforeach
                </div>
                <img src="{{ Storage::url($project->image) }}" alt="Image principale du projet {{ $project->title }}">
                <div class="cta-buttons">
                    @if($project->demo_link)
                        <a href="{{ $project->demo_link }}" class="btn" target="_blank">Voir la Démo</a>
                    @endif
                    @if($project->github_link)
                        <a href="{{ $project->github_link }}" class="btn" target="_blank">Voir le Code Source</a>
                    @endif
                </div>
            </div>

            <!-- COLONNE DE DROITE : L'HISTOIRE DU PROJET -->
            <div class="project-content">
    
    <!-- Affiche la section Problématique SEULEMENT si elle est remplie -->
    @if($project->problematic)
        <h3>Problématique</h3>
        <div class="prose">{!! $project->problematic !!}</div>
    @endif

    <!-- Affiche la section Solution SEULEMENT si elle est remplie -->
    @if($project->solution)
        <h3>Ma Solution</h3>
        <div class="prose">{!! $project->solution !!}</div>
    @endif

    <!-- Affiche la section Apprentissages SEULEMENT si elle est remplie -->
    @if($project->learnings)
        <h3>Défis & Apprentissages</h3>
        <div class="prose">{!! $project->learnings !!}</div>
    @endif
    
</div>

        </div>
    </section>

</body>
</html>