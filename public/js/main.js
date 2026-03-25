// portfolio-laravel/public/js/main.js

document.addEventListener('DOMContentLoaded', function () {

    // -------------------------------------------------------
    // 1. MENU HAMBURGER & SCROLL
    // -------------------------------------------------------
    const menuIcon       = document.querySelector('#menu-icon');
    const navbar         = document.querySelector('.navbar');
    const sections       = document.querySelectorAll('section');
    const navLinks       = document.querySelectorAll('header nav a');
    const header         = document.querySelector('header');
    const backToTopButton = document.querySelector('.back-to-top');

    if (menuIcon && navbar) {
        menuIcon.addEventListener('click', () => {
            menuIcon.classList.toggle('fa-xmark');
            navbar.classList.toggle('active');
        });
    }

    window.addEventListener('scroll', () => {
        // Scrollspy
        sections.forEach(sec => {
            const top    = window.scrollY;
            const offset = sec.offsetTop - 150;
            const height = sec.offsetHeight;
            const id     = sec.getAttribute('id');

            if (top >= offset && top < offset + height) {
                navLinks.forEach(link => link.classList.remove('active'));
                const activeLink = document.querySelector('header nav a[href*=' + id + ']');
                if (activeLink) activeLink.classList.add('active');
            }
        });

        // Header sticky
        if (header) header.classList.toggle('sticky', window.scrollY > 100);

        // Bouton retour en haut
        if (backToTopButton) {
            backToTopButton.classList.toggle('active', window.scrollY > 300);
        }

        // Fermer le menu mobile au scroll
        if (menuIcon && navbar) {
            menuIcon.classList.remove('fa-xmark');
            navbar.classList.remove('active');
        }
    }, { passive: true });

    // -------------------------------------------------------
    // 2. ANTI DOUBLE-SUBMIT (formulaire contact)
    // -------------------------------------------------------
    const contactForm = document.querySelector('.contact form');
    const submitBtn   = document.querySelector('#submit-btn');
    if (contactForm && submitBtn) {
        contactForm.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitBtn.value    = 'Envoi en cours...';
        });
    }

    // -------------------------------------------------------
    // 3. FLIPPER DYNAMIQUE
    // Génère les @keyframes slideInfinite selon le nombre réel
    // de titres configurés dans l'admin (data-count sur le container).
    // Écrase le fallback CSS hardcodé pour 3 titres.
    // -------------------------------------------------------
    const flipperContainer = document.querySelector('.flipper-container');
    if (flipperContainer) {
        const titleCount = parseInt(flipperContainer.dataset.count, 10);
        const firstItem  = flipperContainer.querySelector('.flipper-item');

        if (titleCount > 0 && firstItem) {
            const itemH = firstItem.offsetHeight; // hauteur réelle px (mobile inclus)
            const total = titleCount + 1;         // N titres + 1 doublon pour boucler
            const step  = 100 / total;            // % alloué par titre

            let css = '@keyframes slideInfinite {\n';
            for (let i = 0; i < total; i++) {
                const pStart     = (i * step).toFixed(2);
                const pPause     = (i * step + step * 0.75).toFixed(2);
                const translateY = -(i * itemH);
                if (i === 0) {
                    css += `  0%, ${pPause}% { transform: translateY(0px); }\n`;
                } else {
                    css += `  ${pStart}%, ${pPause}% { transform: translateY(${translateY}px); }\n`;
                }
            }
            css += `  100% { transform: translateY(-${(total - 1) * itemH}px); }\n`;
            css += '}';

            const style = document.createElement('style');
            style.textContent = css;
            document.head.appendChild(style);

            // Force le navigateur à relancer l'animation avec les nouveaux @keyframes
            flipperContainer.style.animation = 'none';
            flipperContainer.offsetHeight; // reflow
            flipperContainer.style.animation = '';
        }
    }

    // -------------------------------------------------------
    // 4. FILTRAGE PROJETS PAR TAG + PAGINATION "Voir plus"
    // -------------------------------------------------------
    const filtreBtns  = document.querySelectorAll('.filtre-btn');
    const projetBoxes = document.querySelectorAll('.projets-container .projets-box');
    const voirPlusBtn = document.getElementById('voir-plus-btn');
    const voirPlusWrap = voirPlusBtn ? voirPlusBtn.parentElement : null;
    const PAGE_SIZE   = 6;

    let currentFilter = 'tous';
    let visibleCount  = PAGE_SIZE;

    function matchesFilter(box, filtre) {
        const tags = (box.dataset.tags || '')
            .split(',')
            .map(t => t.trim().toLowerCase().replace(/\s+/g, '-'));
        return tags.includes(filtre);
    }

    function renderProjects() {
        let totalMatching = 0;
        let shown = 0;

        projetBoxes.forEach(box => {
            const passes = currentFilter === 'tous' || matchesFilter(box, currentFilter);
            if (passes) totalMatching++;
        });

        projetBoxes.forEach(box => {
            const passes = currentFilter === 'tous' || matchesFilter(box, currentFilter);
            if (passes && shown < visibleCount) {
                box.classList.remove('hidden');
                shown++;
            } else {
                box.classList.add('hidden');
            }
        });

        if (voirPlusWrap) voirPlusWrap.hidden = (shown >= totalMatching);
    }

    if (projetBoxes.length) {
        renderProjects();

        filtreBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filtreBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.filtre;
                visibleCount  = PAGE_SIZE;
                renderProjects();
            });
        });

        if (voirPlusBtn) {
            voirPlusBtn.addEventListener('click', () => {
                visibleCount += PAGE_SIZE;
                renderProjects();

            });
        }
    }

    // -------------------------------------------------------
    // 5. BASCULE THÈME CLAIR / SOMBRE
    // -------------------------------------------------------
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon   = document.getElementById('theme-icon');

    function applyTheme(theme) {
        if (theme === 'light') {
            document.documentElement.setAttribute('data-theme', 'light');
            if (themeIcon) { themeIcon.classList.remove('fa-sun'); themeIcon.classList.add('fa-moon'); }
        } else {
            document.documentElement.removeAttribute('data-theme');
            if (themeIcon) { themeIcon.classList.remove('fa-moon'); themeIcon.classList.add('fa-sun'); }
        }
    }

    if (themeToggle) {
        // Sync icon with current theme on load
        const currentTheme = document.documentElement.getAttribute('data-theme');
        applyTheme(currentTheme === 'light' ? 'light' : 'dark');

        themeToggle.addEventListener('click', () => {
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            const next = isLight ? 'dark' : 'light';
            applyTheme(next);
            localStorage.setItem('theme', next);
        });
    }

    // -------------------------------------------------------
    // 6. SCROLL REVEAL (animations d'entrée)
    // -------------------------------------------------------
    if (typeof ScrollReveal !== 'undefined') {
        const sr = ScrollReveal({
            distance: '80px',
            duration: 2000,
            delay: 200,
            reset: false,
        });
        sr.reveal('.accueil-content', { origin: 'top' });
        sr.reveal('.accueil-img', { origin: 'bottom' });
        sr.reveal('.titre-section', { origin: 'left' });
        sr.reveal('.experience-container, .projets-container', { origin: 'bottom' });
    }


});
