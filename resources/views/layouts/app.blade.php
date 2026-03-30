<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1f242d">

    <title>@yield('meta_title', ($personalInfo->name ?? 'Portfolio') . ' | Portfolio')</title>
    <meta name="description" content="@yield('meta_description', 'Portfolio de ' . ($personalInfo->name ?? ''))">

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('og_title', ($personalInfo->name ?? '') . ' | Portfolio')">
    <meta property="og:description" content="@yield('og_description', strip_tags($personalInfo->description ?? ''))">
    <meta property="og:image" content="@yield('og_image', asset('images/image-de-partage.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">

    <!-- Thème : appliqué avant le rendu pour éviter le flash -->
    <script>
        (function(){
            var saved = localStorage.getItem('theme');
            if (saved === 'light') {
                document.documentElement.setAttribute('data-theme', 'light');
            } else if (!saved && window.matchMedia('(prefers-color-scheme: light)').matches) {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>

    <!-- Polices -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @stack('styles')

    <!-- JSON-LD Person (défaut toutes les pages) -->
    @if(!empty($personalInfo->name))
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Person",
        "name": "{{ $personalInfo->name }}",
        "url": "{{ url('/') }}",
        "email": "{{ $personalInfo->email ?? '' }}",
        "sameAs": ["{{ $personalInfo->linkedin ?? '' }}", "{{ $personalInfo->github ?? '' }}"]
    }
    </script>
    @endif
    {{-- Les pages peuvent injecter leur propre JSON-LD via @push('json_ld') --}}
    @stack('json_ld')

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7EZN4396E1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-7EZN4396E1');
    </script>
</head>
<body class="@yield('body_class')">

    <a href="#accueil" class="skip-link">Aller au contenu principal</a>

    @section('header')
        <header class="header">
            <a href="{{ route('home') }}" class="logo">Portfolio MLG</a>
            <button class="theme-toggle" id="theme-toggle" aria-label="Basculer le thème clair/sombre">
                <i class="fa-solid fa-sun" id="theme-icon"></i>
            </button>
            <i class="fa-solid fa-bars" id="menu-icon"></i>
            <nav class="navbar">
                <a href="{{ route('home') }}#accueil" class="active">Accueil</a>
                <a href="{{ route('home') }}#experience">Expérience</a>
                <a href="{{ route('home') }}#technologies">Technologies</a>
                <a href="{{ route('home') }}#projets">Projets</a>
                <a href="{{ route('home') }}#contact">Contact</a>
            </nav>
        </header>
    @show

    @yield('content')

    <footer class="footer">
        <div class="footer-simple">
            <span class="footer-name">{{ $personalInfo->name ?? 'Portfolio' }}</span>

            <div class="footer-social-icons">
                @if(!empty($personalInfo->linkedin))
                <a href="{{ $personalInfo->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
                @endif
                @if(!empty($personalInfo->github))
                <a href="{{ $personalInfo->github }}" target="_blank" rel="noopener noreferrer" aria-label="GitHub">
                    <i class="fa-brands fa-github"></i>
                </a>
                @endif
                @if(!empty($personalInfo->email))
                <a href="https://mail.google.com/mail/u/0/?view=cm&fs=1&to={{ $personalInfo->email }}" target="_blank" rel="noopener noreferrer" aria-label="Email">
                    <i class="fa-solid fa-envelope"></i>
                </a>
                @endif
                @if(!empty($personalInfo->phone))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $personalInfo->phone) }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                @endif
            </div>

            <p class="footer-copy">&copy; {{ date('Y') }} — Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Boutons flottants contact -->
    <div class="floating-contact">
        @if(!empty($personalInfo->phone))
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $personalInfo->phone) }}"
           class="floating-btn floating-whatsapp"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Contacter sur WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        @endif
        @if(!empty($personalInfo->email))
        <a href="https://mail.google.com/mail/u/0/?view=cm&amp;fs=1&amp;to={{ $personalInfo->email }}"
           class="floating-btn floating-email"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="Envoyer un email via Gmail">
            <i class="fa-solid fa-envelope"></i>
        </a>
        @endif
    </div>

    <a href="#" class="back-to-top"><i class="fa-solid fa-arrow-up"></i></a>

    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
