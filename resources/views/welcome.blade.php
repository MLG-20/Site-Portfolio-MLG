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

            <div class="description">{!! $personalInfo->description !!}</div>

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
                    @if($exp->certificate_path)
                        @php
                            $isDiplome = $exp->document_type === 'diplome';
                            $btnLabel  = $isDiplome ? 'Voir le diplôme' : 'Voir le certificat';
                            $btnIcon   = $isDiplome ? 'fa-solid fa-graduation-cap' : 'fa-solid fa-award';
                        @endphp
                        <button
                            class="btn-certificate"
                            data-certificate="{{ Storage::url($exp->certificate_path) }}"
                            data-title="{{ $exp->title }}"
                            data-type="{{ str_ends_with(strtolower($exp->certificate_path), '.pdf') ? 'pdf' : 'image' }}"
                            aria-label="{{ $btnLabel }} de {{ $exp->title }}">
                            <i class="{{ $btnIcon }}"></i> {{ $btnLabel }}
                        </button>
                    @endif
                </div>
            @endforeach

            <!-- MODAL CERTIFICAT -->
            <div class="certificate-modal" id="certificate-modal" role="dialog" aria-modal="true" aria-hidden="true">
                <div class="certificate-modal-overlay" id="certificate-overlay"></div>
                <div class="certificate-modal-content">
                    <button class="certificate-modal-close" id="certificate-close" aria-label="Fermer">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    <h3 class="certificate-modal-title" id="certificate-modal-title"></h3>
                    <img src="" alt="Certificat" id="certificate-img" class="certificate-modal-img">
                    <embed src="" id="certificate-pdf" class="certificate-modal-pdf" type="application/pdf">
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION TECHNOLOGIES -->
    @if($technologies->isNotEmpty())
    <section class="technologies" id="technologies">
        <h2 class="titre-section">Mes <span>Technologies</span></h2>

        @foreach($technologies as $category => $techs)
            <div class="technologies-group">
                <h3 class="technologies-category">{{ $category }}</h3>
                <div class="technologies-grid">
                    @foreach($techs as $tech)
                        <div class="technologies-item">
                            <img src="{{ $tech->icon_url }}" alt="{{ $tech->name }}" loading="lazy">
                            <span>{{ $tech->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
    @endif

    <!-- SECTION PROJETS -->
    <section class="projets" id="projets">
        <h2 class="titre-section">Mes <span>Projets</span></h2>

        @if($apps->isNotEmpty())
            <h3 class="projets-sous-titre">Applications & <span>Web</span></h3>
            <div class="projets-container">
                @foreach($apps as $projet)
                    <x-project-card :project="$projet" />
                @endforeach
            </div>
        @endif

        @if($dataProjects->isNotEmpty())
            <h3 class="projets-sous-titre projets-sous-titre--data">Analyse de <span>Données</span></h3>
            <div class="projets-container">
                @foreach($dataProjects as $projet)
                    <x-project-card :project="$projet" />
                @endforeach
            </div>
        @endif

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
