<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $personalInfo->name }} | Portfolio</title>

    <!-- On ajoute ?v=2 à la fin pour forcer le navigateur à oublier l'ancien logo Laravel -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=2">
    
    <!-- META TAGS (SEO & Social) -->
    <meta name="description" content="Découvrez le portfolio de Mamadou Lamine Gueye, Développeur Web et Data Analyst.">
    <meta property="og:title" content="{{ $personalInfo->name }} | Développeur & Data Analyst">
    <meta property="og:description" content="Le pont entre la technique et la stratégie d'entreprise.">
    <meta property="og:image" content="{{ asset('images/image-de-partage.jpg') }}">
    <meta property="og:url" content="https://www.tonfutursite.com/">
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-7EZN4396E1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-7EZN4396E1');
    </script>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <a href="#" class="logo">Portfolio MLG</a>
        <i class="fa-solid fa-bars" id="menu-icon"></i>
        <nav class="navbar">
            <a href="#accueil" class="active">Accueil</a>
            <a href="#experience">Expérience</a>
            <a href="#projets">Projets</a>
            <a href="#contact">Contact</a>
        </nav>
    </header>

    <!-- SECTION ACCUEIL -->
    <section class="accueil" id="accueil">
        <div class="accueil-content">
            <h3>Bonjour, je suis </h>
            <h1>{{ $personalInfo->name }}</h1>
            @php
    // 1. On s'assure d'avoir un tableau
    $titles = $personalInfo->titles;
    if (is_string($titles)) {
        $decoded = json_decode($titles, true);
        $titles = is_array($decoded) ? $decoded : explode(',', $titles);
    }
    $titles = array_values((array)($titles ?? ["Développeur", "Data Analyst", "Expert BI"]));
@endphp

<h3 class="metiers-flipper notranslate">
    Et je suis 
    <span class="flipper-wrapper">
        <span class="flipper-container">
            @foreach($titles as $title)
                <span class="flipper-item">{{ $title }}</span>
            @endforeach
            
            {{-- LE SECRET DE LA BOUCLE : On répète le tout premier mot à la fin --}}
            @if(count($titles) > 0)
                <span class="flipper-item">{{ $titles[0] }}</span>
            @endif
        </span>
    </span>
</h3>


            <p>{{ $personalInfo->description }}</p>
            <div class="social-media">
                <a href="{{ $personalInfo->linkedin }}" target="_blank"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="{{ $personalInfo->github }}" target="_blank"><i class="fa-brands fa-github"></i></a>
                <a href="mailto:{{ $personalInfo->email }}"><i class="fa-solid fa-envelope"></i></a>
                <a href="tel:{{ str_replace(' ', '', $personalInfo->phone) }}"><i class="fa-solid fa-phone"></i></a>
            </div>
            <a href="{{ Storage::url($personalInfo->cv_path) }}" class="btn" download>Télécharger CV</a>
        </div>
        <div class="accueil-img">
            <img src="{{ Storage::url($personalInfo->profile_image) }}" alt="Photo de profil" loading="lazy">
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
                <p><strong>{{ $exp->duration }}</strong></p>
                <p>{{ $exp->description }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <!-- SECTION PROJETS -->
    <section class="projets" id="projets">
        <h2 class="titre-section">Mes <span>Projets</span></h2>
        <div class="projets-container">
            @foreach($projects as $projet)
            <div class="projets-box">
                <img src="{{ Storage::url($projet->image) }}" alt="{{ $projet->title }}" loading="lazy">
                <div class="projets-layer">
                    <a href="{{ route('projects.show', $projet) }}" class="project-title-link">
                        <h4>{{ $projet->title }}</h4>
                    </a>
                    <div class="project-links">
                        @if($projet->github_link)
                            <a href="{{ $projet->github_link }}" target="_blank" title="Voir sur GitHub"><i class="fa-brands fa-github"></i></a>
                        @endif
                        @if($projet->demo_link)
                            <a href="{{ $projet->demo_link }}" target="_blank" title="Voir la démo"><i class="fa-solid fa-up-right-from-square"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- SECTION CONTACT -->
    <section class="contact" id="contact">
        <h2 class="titre-section">Contactez-<span>moi !</span></h2>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('contact.store') }}" method="POST">
            @csrf 
            <div class="input-box">
                <input type="text" name="name" placeholder="Nom Complet" required>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="number" name="phone" placeholder="Téléphone">
                <input type="text" name="subject" placeholder="Sujet" required>
            </div>
            <textarea name="message" cols="30" rows="10" placeholder="Message" required></textarea>
            <input type="submit" value="Envoyer" class="btn">
        </form>
    </section>
    
    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-text">
            <p>Copyright &copy; {{ date('Y') }} {{ $personalInfo->name }} | Tous droits réservés.</p>
        </div>
    </footer>

     <!-- =============================================================== -->
    <!--          BLOC DE SCRIPTS FINAL ET CORRIGÉ                       -->
    <!-- =============================================================== -->

    <!-- 1. On charge toutes les librairies externes d'abord -->
    <script src="https://unpkg.com/scrollreveal"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>
    <!-- 2. Ensuite, on charge ton script principal qui les utilise -->
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Bouton Retour en Haut -->
    <a href="#accueil" class="back-to-top"><i class="fa-solid fa-arrow-up"></i></a>
    
</body>
</html>
