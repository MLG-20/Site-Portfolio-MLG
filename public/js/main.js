// --- Fichier: js/main.js ---

// 1. SCROLLSPY (MET EN ÉVIDENCE LE LIEN DU MENU CORRESPONDANT À LA SECTION VISIBLE)
let sections = document.querySelectorAll('section');
let navLinks = document.querySelectorAll('header nav a');

window.onscroll = () => {
    sections.forEach(sec => {
        let top = window.scrollY;
        let offset = sec.offsetTop - 150;
        let height = sec.offsetHeight;
        let id = sec.getAttribute('id');

        if (top >= offset && top < offset + height) {
            navLinks.forEach(links => {
                links.classList.remove('active');
                document.querySelector('header nav a[href*=' + id + ']').classList.add('active');
            });
        }
    });

    // 2. HEADER "STICKY" (AJOUTE UNE OMBRE QUAND ON SCROLLE)
    let header = document.querySelector('header');
    header.classList.toggle('sticky', window.scrollY > 100);

    // 3. FERMER LE MENU HAMBURGER AU CLIC SUR UN LIEN (POUR MOBILE)
    menuIcon.classList.remove('fa-xmark');
    navbar.classList.remove('active');
};

// 4. GESTION DU MENU HAMBURGER
let menuIcon = document.querySelector('#menu-icon');
let navbar = document.querySelector('.navbar');

menuIcon.onclick = () => {
    menuIcon.classList.toggle('fa-xmark'); // Change l'icône
    navbar.classList.toggle('active'); // Affiche/cache le menu
};

// 5. ANIMATION D'ÉCRITURE POUR LE TITRE DYNAMIQUE
// Assurez-vous d'avoir une variable PHP 'titres' convertie en JSON dans votre HTML
const typed = new Typed('.multiple-text', {
    strings: ['Développeur Web', 'Analyste de Données', 'Créateur de Solutions'], // Valeurs par défaut
    typeSpeed: 100,
    backSpeed: 100,
    backDelay: 1000,
    loop: true
    
});
/* --- Dans public/js/main.js --- */

// 6. ANIMATIONS AU DÉFILEMENT AVEC SCROLLREVEAL
const sr = ScrollReveal({
    distance: '80px',
    duration: 2000,
    delay: 200,
    reset: true // L'animation se rejoue à chaque fois
});

// Animation pour l'accueil
sr.reveal('.accueil-content', { origin: 'top' });
sr.reveal('.accueil-img', { origin: 'bottom' });

// Animation pour les autres sections
sr.reveal('.titre-section', { origin: 'left' });
sr.reveal('.experience-container, .projets-container, .contact form', { origin: 'bottom' });

// Récupération dynamique des titres depuis le PHP (si le script est dans le HTML)
// Si le script reste dans un fichier .js externe, cette partie doit être dans le HTML
// <script>
// const typed = new Typed('.multiple-text', {
//     strings: <?= json_encode($portfolio['personal_info']['titles']) ?>,
//     ...
// });
// </script>