@extends('layouts.app')

@section('meta_title', $project->title . ' | ' . $personalInfo->name)
@section('meta_description', strip_tags(\Illuminate\Support\Str::limit($project->description ?? '', 160)))
@section('og_title', $project->title)
@section('og_description', strip_tags(\Illuminate\Support\Str::limit($project->description ?? '', 200)))
@section('og_image', Storage::url($project->image))
@section('body_class', 'project-detail-page')

@push('json_ld')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@type": "SoftwareSourceCode",
    "name": "{{ addslashes($project->title) }}",
    "description": "{{ addslashes(strip_tags($project->description ?? '')) }}",
    "url": "{{ url()->current() }}"
    @if($project->github_link)
    ,"codeRepository": "{{ $project->github_link }}"
    @endif
    @if(!empty($project->tags))
    ,"programmingLanguage": {{ json_encode($project->tags) }}
    @endif
}
</script>
@endpush

@section('header')
    <header class="header sticky">
        <a href="{{ route('home') }}" class="logo">
            <i class="fa-solid fa-arrow-left"></i> Retour au Portfolio
        </a>
    </header>
@endsection

@section('content')

    <section class="project-detail">
        <div class="project-detail-container">

            <!-- COLONNE GAUCHE : visuels et infos clés -->
            <div class="project-header">
                <h1>{{ $project->title }}</h1>

                <div class="tags-container">
                    @foreach(is_array($project->tags) ? $project->tags : [] as $tag)
                        <x-tag :label="trim($tag)" />
                    @endforeach
                </div>

                <p class="project-views">
                    <i class="fa-solid fa-eye"></i> {{ $project->views_count }} vue{{ $project->views_count !== 1 ? 's' : '' }}
                </p>

                <img src="{{ Storage::url($project->image) }}" alt="Image principale du projet {{ $project->title }}">

                <div class="cta-buttons">
                    @if($project->demo_link)
                        <a href="{{ $project->demo_link }}" class="btn" target="_blank" rel="noopener noreferrer">Voir la Démo</a>
                    @endif
                    @if($project->github_link)
                        <a href="{{ $project->github_link }}" class="btn" target="_blank" rel="noopener noreferrer">Voir le Code Source</a>
                    @endif
                </div>
            </div>

            <!-- COLONNE DROITE : l'histoire du projet -->
            <div class="project-content">

                @if($project->video_path)
                    <div class="project-video">
                        <h3>Démonstration</h3>
                        <video controls preload="metadata" playsinline>
                            <source src="{{ Storage::url($project->video_path) }}">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    </div>
                @endif

                @if($project->problematic)
                    <h3>Problématique</h3>
                    <div class="prose">{!! $project->problematic !!}</div>
                @endif

                @if($project->solution)
                    <h3>Ma Solution</h3>
                    <div class="prose">{!! $project->solution !!}</div>
                @endif

                @if($project->learnings)
                    <h3>Défis & Apprentissages</h3>
                    <div class="prose">{!! $project->learnings !!}</div>
                @endif

            </div>

        </div>
    </section>

@endsection
