@extends('layouts.app')

@section('meta_description', 'Découvrez le portfolio de ' . ($personalInfo->name ?? '') . ', Développeur Web et Data Analyst.')
@section('og_description', 'Le pont entre la technique et la stratégie d\'entreprise.')

@section('content')

    <!-- SECTION ACCUEIL -->
    <section class="accueil" id="accueil" aria-label="Accueil">
        <div class="accueil-content">
            <h3>Bonjour, je suis </h3>
            <h1>{{ $personalInfo->name }}</h1>

            <h3 class="metiers-flipper notranslate">
                <span class="flipper-prefix">Et je suis</span>
                <span class="flipper-wrapper">
                    <span class="flipper-container" data-count="{{ count($personalInfo->titles ?? []) }}">
                        @foreach($personalInfo->titles ?? [] as $title)
                            <span class="flipper-item">{{ $title }}</span>
                        @endforeach
                        {{-- Doublon du premier titre pour boucler l'animation --}}
                        @if(!empty($personalInfo->titles))
                            <span class="flipper-item">{{ $personalInfo->titles[0] }}</span>
                        @endif
                    </span>
                </span>
            </h3>

            <p>{{ $personalInfo->description }}</p>

            <div class="social-media">
                <a href="{{ $personalInfo->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="Profil LinkedIn de {{ $personalInfo->name }}">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
                <a href="{{ $personalInfo->github }}" target="_blank" rel="noopener noreferrer" aria-label="Profil GitHub de {{ $personalInfo->name }}">
                    <i class="fa-brands fa-github"></i>
                </a>
                <a href="https://mail.google.com/mail/u/0/?view=cm&amp;fs=1&amp;to={{ $personalInfo->email }}" target="_blank" rel="noopener noreferrer" aria-label="Envoyer un email à {{ $personalInfo->name }}">
                    <i class="fa-solid fa-envelope"></i>
                </a>
                <a href="tel:{{ str_replace(' ', '', $personalInfo->phone) }}" aria-label="Appeler {{ $personalInfo->name }}">
                    <i class="fa-solid fa-phone"></i>
                </a>
            </div>

            <a href="{{ Storage::url($personalInfo->cv_path) }}" class="btn" download>Télécharger CV</a>
        </div>

        <div class="accueil-img">
            <img src="{{ Storage::url($personalInfo->profile_image) }}" alt="Photo de profil de {{ $personalInfo->name }}" loading="lazy">
        </div>
    </section>

    <!-- SECTION EXPÉRIENCE -->
    <section class="experience" id="experience">
        <h2 class="titre-section">Mon <span>Parcours</span></h2>
        <div class="experience-container">
            @foreach($experiences as $exp)
                <div class="experience-box">
                    <i class="{{ $exp->icon }}"></i>
                    <h3>{{ $exp->title }}</h3>
                    @if($exp->company)
                        <p class="experience-company">{{ $exp->company }}</p>
                    @endif
                    <p><strong>{{ $exp->duration }}</strong></p>
                    <p>{{ $exp->description }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- SECTION PROJETS -->
    <section class="projets" id="projets">
        <h2 class="titre-section">Mes <span>Projets</span></h2>

        @if($allTags->isNotEmpty())
        <div class="projets-filtres" aria-label="Filtrer les projets par technologie">
            <button class="filtre-btn active" data-filtre="tous">Tous</button>
            @foreach($allTags as $tag)
                <button class="filtre-btn" data-filtre="{{ strtolower(preg_replace('/\s+/', '-', $tag)) }}" data-label="{{ $tag }}">{{ $tag }}</button>
            @endforeach
        </div>
        @endif

        <div class="projets-container">
            @foreach($projects as $projet)
                <x-project-card :project="$projet" />
            @endforeach
        </div>

        <div class="projets-voir-plus">
            <button id="voir-plus-btn" class="btn">Voir plus</button>
        </div>
    </section>

    <!-- SECTION CONTACT -->
    <section class="contact" id="contact">
        <h2 class="titre-section">Contactez-<span>moi !</span></h2>

        <div class="contact-container">

            <!-- Formulaire -->
            <div class="contact-form">
                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="input-box">
                        <div class="input-group">
                            <input type="text" name="name" placeholder="Nom Complet" value="{{ old('name') }}" required>
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="input-group">
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="input-box">
                        <div class="input-group">
                            <input type="tel" name="phone" placeholder="Téléphone" value="{{ old('phone') }}">
                            @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="input-group">
                            <input type="text" name="subject" placeholder="Sujet" value="{{ old('subject') }}" required>
                            @error('subject') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <textarea name="message" cols="30" rows="10" placeholder="Message" required>{{ old('message') }}</textarea>
                    @error('message') <span class="form-error">{{ $message }}</span> @enderror
                    <input type="submit" value="Envoyer" class="btn" id="submit-btn">
                </form>
            </div>

        </div>
    </section>

@endsection
