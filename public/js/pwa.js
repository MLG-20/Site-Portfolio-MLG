// Enregistrement du Service Worker pour PWA
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/service-worker.js')
      .then(registration => {
        console.log('✅ Service Worker enregistré:', registration);
        
        // Vérifier les mises à jour toutes les heures
        setInterval(() => {
          registration.update();
        }, 60 * 60 * 1000);
      })
      .catch(error => {
        console.warn('⚠️ Erreur Service Worker:', error);
      });

    // Listener pour les updates du Service Worker
    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.addEventListener('controllerchange', () => {
        console.log('🔄 Service Worker mis à jour!');
        // Optionnel: afficher une notification à l'utilisateur
      });
    }
  });

  // Listener pour les messages du Service Worker
  navigator.serviceWorker.addEventListener('message', event => {
    if (event.data.type === 'NEW_VERSION_AVAILABLE') {
      console.log('📦 Nouvelle version disponible!');
      // Afficher une notification
      if (Notification.permission === 'granted') {
        new Notification('Mise à jour disponible', {
          body: 'Une nouvelle version de votre portfolio est disponible.',
          icon: '/images/icons/icon-192.png',
          tag: 'portfolio-update'
        });
      }
    }
  });

  // Demander la permission pour les notifications
  if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission();
  }
}

// Installation de la PWA (améliorer le prompt d'installation)
let deferredPrompt;

window.addEventListener('beforeinstallprompt', event => {
  // Empêcher le mini-infobar d'apparaître automatiquement
  event.preventDefault();
  // Stocker l'événement pour l'utiliser plus tard
  deferredPrompt = event;
  
  // Afficher un bouton d'installation personnalisé si disponible
  const installButton = document.getElementById('install-pwa-btn');
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', async () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log(`Installation ${outcome}`);
        deferredPrompt = null;
        installButton.style.display = 'none';
      }
    });
  }
});

window.addEventListener('appinstalled', () => {
  console.log('🎉 PWA installée!');
  // Tracker l'installation (optionnel: envoyer à analytics)
});
