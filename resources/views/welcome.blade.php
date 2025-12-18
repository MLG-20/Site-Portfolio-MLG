<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $personalInfo->name }} | Portfolio</title>
    
    <!-- Liens CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Note la fonction asset() qui pointe vers le dossier public -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Google tag (gtag.js) -->
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
            <h3>Bonjour, je suis</h3>
            <h1>{{ $personalInfo->name }}</h1>
            <!-- On utilisera JS pour les titres dynamiques -->
            <h3>Et je suis <span class="multiple-text"></span></h3>
            <p>{{ $personalInfo->description }}</p>
            <div class="social-media">
                <a href="{{ $personalInfo->linkedin }}"><i class="fa-brands fa-linkedin-in"></i></a>
                <a href="{{ $personalInfo->github }}"><i class="fa-brands fa-github"></i></a>
                <a href="mailto:{{ $personalInfo->email }}"><i class="fa-solid fa-envelope"></i></a>
                <a href="tel:{{ str_replace(' ', '', $personalInfo->phone) }}"><i class="fa-solid fa-phone"></i>
            </div>
            <a href="{{ Storage::url($personalInfo->cv_path) }}" class="btn" download>Télécharger CV</a>
        </div>
        <div class="accueil-img">
            <img src="{{ Storage::url($personalInfo->profile_image) }}" alt="Photo de profil">
        </div>
         <!-- AJOUTE LE SÉPARATEUR ICI -->
    <div class="custom-shape-divider-bottom-1702822476" style="background-color: var(--bg-color);">
        <svg data-name="Layer 1" ... >
            <path d="..." class="shape-fill" fill="#323946"></path> <!-- La couleur de la section suivante -->
        </svg>
    </div>
    </section>

    <!-- SECTION EXPÉRIENCE (Boucle dynamique) -->
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

    <!-- SECTION PROJETS (Boucle dynamique) -->
    <section class="projets" id="projets">
        <h2 class="titre-section">Mes <span>Projets</span></h2>
        <div class="projets-container">
            @foreach($projects as $projet)
            <div class="projets-box">
                <img src="{{ Storage::url($projet->image) }}" alt="{{ $projet->title }}">
                <div class="projets-layer">
                    <h4>{{ $projet->title }}</h4>
                    <p>{{Str::limit($projet->description, 100) }}</p>
                    <div class="tags">
                        @php
                            // 1. On récupère la valeur brute
                            $rawTags = $projet->tags;

                            // 2. Si c'est du texte (String), on le décode en JSON
                            if (is_string($rawTags)) {
                                $tags = json_decode($rawTags, true);
                            } 
                            // 3. Si c'est déjà un tableau (Array), on le garde
                            elseif (is_array($rawTags)) {
                                $tags = $rawTags;
                            }
                            // 4. Sinon (null ou autre), on met un tableau vide pour ne pas planter
                            else {
                                $tags = [];
                            }
                        @endphp

                        @foreach($tags ?? [] as $tag)
                            <span style="font-size: 1.2rem; background:var(--main-color); color:#000; padding:2px 8px; border-radius:4px; margin-right:5px;">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                    <a href="{{ $projet->demo_link }}"><i class="fa-solid fa-up-right-from-square"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- CONTACT (Avec le formulaire Laravel) -->
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

    <!-- SCRIPTS -->
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <!-- Script pour l'animation de texte dynamique depuis la BDD -->
    <script>
        const typed = new Typed('.multiple-text', {
            // On convertit le tableau PHP en JSON pour le JS
            strings: @json($personalInfo->titles), 
            typeSpeed: 100,
            backSpeed: 100,
            backDelay: 1000,
            loop: true
        });
    </script>
    <script src="https://unpkg.com/scrollreveal"></script> <!-- <-- AJOUTE CETTE LIGNE -->
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>