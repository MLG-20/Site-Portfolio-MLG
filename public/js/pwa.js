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

// Système de polling pour les nouveaux messages
let messagePollingInterval = null;
let lastMessageId = null;

function startMessagePolling() {
  // Vérifier les messages toutes les 30 secondes
  messagePollingInterval = setInterval(checkForNewMessages, 30 * 1000);

  // Vérifier immédiatement au démarrage
  checkForNewMessages();
}

function stopMessagePolling() {
  if (messagePollingInterval) {
    clearInterval(messagePollingInterval);
    messagePollingInterval = null;
  }
}

async function checkForNewMessages() {
  try {
    const response = await fetch('/api/check-messages', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      credentials: 'include', // Inclure les cookies de session
    });

    if (!response.ok) {
      console.warn('⚠️ Erreur lors de la vérification des messages:', response.status);
      return;
    }

    const data = await response.json();

    // Si des nouveaux messages non lus existent
    if (data.hasNewMessages && data.lastMessage) {
      // Éviter les doublons avec le lastMessageId
      if (lastMessageId !== data.lastMessage.id) {
        lastMessageId = data.lastMessage.id;
        showMessageNotification(data.lastMessage, data.unreadCount);
      }
    }
  } catch (error) {
    console.warn('⚠️ Erreur polling messages:', error);
  }
}

function showMessageNotification(message, unreadCount) {
  // Vérifier la permission des notifications
  if (!('Notification' in window)) {
    console.warn('⚠️ Les notifications ne sont pas supportées');
    return;
  }

  if (Notification.permission !== 'granted') {
    console.warn('⚠️ Permission de notification refusée');
    return;
  }

  // Créer la notification
  const title = `📩 Nouveau message de ${message.name}`;
  const options = {
    body: message.subject ? message.subject.substring(0, 50) + '...' : 'Vous avez un nouveau message',
    icon: '/images/icons/icon-192.png',
    badge: '/images/icons/icon-96.png',
    tag: 'new-message',
    requireInteraction: true,
    data: {
      messageId: message.id,
      timestamp: message.created_at
    },
    actions: [
      {
        action: 'open-dashboard',
        title: 'Voir le message'
      },
      {
        action: 'close',
        title: 'Fermer'
      }
    ]
  };

  // Montrer le nombre de messages non lus dans le titre si > 1
  if (unreadCount > 1) {
    const titleWithCount = `📩 ${unreadCount} nouveaux messages`;
    new Notification(titleWithCount, options);
  } else {
    new Notification(title, options);
  }

  // Jouer un son si disponible
  playNotificationSound();
}

function playNotificationSound() {
  // Créer un son avec l'API Web Audio API (son simple de notification)
  try {
    const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    const oscillator = audioContext.createOscillator();
    const gainNode = audioContext.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(audioContext.destination);

    oscillator.frequency.value = 800; // Fréquence en Hz
    oscillator.type = 'sine';

    gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

    oscillator.start(audioContext.currentTime);
    oscillator.stop(audioContext.currentTime + 0.5);
  } catch (error) {
    console.warn('⚠️ Impossible de jouer le son:', error);
  }
}

// Démarrer le polling au chargement de la page
window.addEventListener('load', () => {
  // Vérifier s'il y a au moins une notification ou si la page est visible
  if ('Notification' in window && Notification.permission === 'granted') {
    startMessagePolling();

    // Arrêter le polling si l'utilisateur quitte la page
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        // Pour une meilleure économie de batterie, optionnel
        // stopMessagePolling();
      } else {
        // Relancer si ce n'est pas actif
        if (!messagePollingInterval) {
          startMessagePolling();
        }
      }
    });
  }
});

// Gérer les clics sur les notifications
navigator.serviceWorker.addEventListener('message', event => {
  if (event.data.type === 'NOTIFICATION_CLICK') {
    if (event.data.action === 'open-dashboard') {
      // Ouvrir le lien du dashboard Filament
      window.open('/admin/dashboard', '_blank');
    }
  }
});

