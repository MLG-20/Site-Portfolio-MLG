// Enregistrement du Service Worker pour PWA
let updateAvailable = false;
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/service-worker.js')
      .then(registration => {
        console.log('✅ Service Worker enregistré:', registration);

        // Vérifier les mises à jour toutes les 30 minutes
        setInterval(() => {
          registration.update();
        }, 30 * 60 * 1000);

        // Vérifier une fois au chargement
        registration.update();
      })
      .catch(error => {
        console.warn('⚠️ Erreur Service Worker:', error);
      });

    // Listener pour les updates du Service Worker
    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.addEventListener('controllerchange', () => {
        console.log('🔄 Service Worker mis à jour!');
        if (updateAvailable) {
          window.location.reload();
        }
      });
    }
  });

  // Listener pour les messages du Service Worker
  navigator.serviceWorker.addEventListener('message', event => {
    if (event.data.type === 'NEW_VERSION_AVAILABLE') {
      console.log('📦 Nouvelle version disponible!');
      updateAvailable = true;
      showUpdateNotification();
    }
  });
}

// Afficher une notification de mise à jour
function showUpdateNotification() {
  const notification = document.createElement('div');
  notification.id = 'pwa-update-notification';
  notification.style.cssText = `
    position: fixed;
    bottom: 2rem;
    left: 1rem;
    right: 1rem;
    background: linear-gradient(45deg, #00c6ff, #0072ff);
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 198, 255, 0.3);
    z-index: 9999;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
  `;

  notification.innerHTML = `
    <span>✨ Une nouvelle version est disponible!</span>
    <button id="pwa-update-btn" style="
      background: white;
      color: #00c6ff;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 0.3rem;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    ">Mettre à jour</button>
  `;

  document.body.appendChild(notification);

  const updateBtn = document.getElementById('pwa-update-btn');
  updateBtn.addEventListener('mouseover', () => {
    updateBtn.style.background = '#f0f4f8';
    updateBtn.style.transform = 'scale(1.05)';
  });
  updateBtn.addEventListener('mouseout', () => {
    updateBtn.style.background = 'white';
    updateBtn.style.transform = 'scale(1)';
  });
  updateBtn.addEventListener('click', () => {
    // Recharger la page
    navigator.serviceWorker.ready.then(() => {
      window.location.reload();
    });
  });

  // Notification du navigateur aussi
  if (Notification.permission === 'granted') {
    new Notification('Mise à jour disponible', {
      body: 'Une nouvelle version de votre portfolio est disponible. Clique pour mettre à jour !',
      icon: '/images/icons/icon-192.png',
      tag: 'portfolio-update',
      requireInteraction: true
    });
  }

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
