// --- Fichier: js/main.js (Version Complète et Corrigée) ---

// S'assure que tout le code s'exécute après le chargement complet de la page
document.addEventListener('DOMContentLoaded', function() {

    // --- 1. GESTION DU MENU & SCROLL ---
    const menuIcon = document.querySelector('#menu-icon');
    const navbar = document.querySelector('.navbar');
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('header nav a');
    const header = document.querySelector('header');
    const backToTopButton = document.querySelector('.back-to-top');

    // Gestion du menu hamburger
    if (menuIcon && navbar) {
        menuIcon.onclick = () => {
            menuIcon.classList.toggle('fa-xmark');
            navbar.classList.toggle('active');
        };
    }
    
    // Actions au défilement de la page
    window.onscroll = () => {
        // Scrollspy (met en évidence le lien du menu)
        sections.forEach(sec => {
            let top = window.scrollY;
            let offset = sec.offsetTop - 150;
            let height = sec.offsetHeight;
            let id = sec.getAttribute('id');

            if (top >= offset && top < offset + height) {
                navLinks.forEach(links => {
                    links.classList.remove('active');
                    const activeLink = document.querySelector('header nav a[href*=' + id + ']');
                    if (activeLink) {
                        activeLink.classList.add('active');
                    }
                });
            }
        });

        // Header "sticky"
        if (header) {
            header.classList.toggle('sticky', window.scrollY > 100);
        }

        // Bouton "Retour en haut"
        if (backToTopButton) {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        }

        // Fermer le menu mobile au scroll
        if (menuIcon && navbar) {
            menuIcon.classList.remove('fa-xmark');
            navbar.classList.remove('active');
        }
    };

    // --- 2. ANIMATIONS AU CHARGEMENT (ScrollReveal) ---
    if (typeof ScrollReveal !== 'undefined') {
        const sr = ScrollReveal({
            distance: '80px',
            duration: 2000,
            delay: 200,
            reset: false // On joue l'animation une seule fois
        });
        
        sr.reveal('.accueil-content', { origin: 'top' });
        sr.reveal('.accueil-img', { origin: 'bottom' });
        sr.reveal('.titre-section', { origin: 'left' });
        sr.reveal('.experience-container, .projets-container, .contact form', { origin: 'bottom' });
    }

    // --- 3. EFFET 3D TILT SUR LES CARTES DE PROJET ---
    if (typeof VanillaTilt !== 'undefined') {
        VanillaTilt.init(document.querySelectorAll(".projets-box"), {
            max: 15, // Un effet plus subtil
            speed: 400,
            glare: true,
            "max-glare": 0.3,
        });
    }

}); // Fin du DOMContentLoaded