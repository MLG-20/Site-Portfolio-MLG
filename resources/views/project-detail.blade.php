@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4/dist/css/splide.min.css">
<style>
.project-slider {
    margin-bottom: 3rem;
}
.project-slider .splide__track {
    border-radius: 1.5rem;
    box-shadow: 0 1rem 3rem rgba(0,0,0,0.3);
}
.project-slider .splide__slide img {
    width: 100%;
    height: 55rem;
    object-fit: cover;
    border-radius: 1.5rem;
    display: block;
}
@media (max-width: 768px) {
    .project-slider .splide__slide img {
        height: 28rem;
    }
}
.project-slider .splide__pagination__page.is-active {
    background: var(--main-color, #00d4ff);
}
.project-slider .splide__arrow {
    background: rgba(0,0,0,0.5);
}
.project-slider .splide__arrow svg {
    fill: #fff;
}
</style>
@endpush

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

                @php $galleryImages = $project->gallery_images ?? []; @endphp

                @if(count($galleryImages) > 0)
                    <div class="splide project-slider" aria-label="Galerie du projet {{ $project->title }}">
                        <div class="splide__track">
                            <ul class="splide__list">
                                <li class="splide__slide">
                                    <img src="{{ Storage::url($project->image) }}" alt="Image principale du projet {{ $project->title }}">
                                </li>
                                @foreach($galleryImages as $img)
                                    <li class="splide__slide">
                                        <img src="{{ Storage::url($img) }}" alt="Image galerie {{ $project->title }}">
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @else
                    <img src="{{ Storage::url($project->image) }}" alt="Image principale du projet {{ $project->title }}">
                @endif

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

@push('scripts')
@php $hasGallery = count($project->gallery_images ?? []) > 0; @endphp
@if($hasGallery)
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4/dist/js/splide.min.js"></script>
<script>
    new Splide('.project-slider', {
        type: 'loop',
        perPage: 1,
        autoplay: false,
        pagination: true,
        arrows: true,
        gap: '1rem',
    }).mount();
</script>
@endif
@endpush
