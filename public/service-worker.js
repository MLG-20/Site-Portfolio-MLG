const CACHE_NAME = 'portfolio-mlg-' + Date.now();
const CACHE_PREFIX = 'portfolio-mlg-';
const STATIC_ASSETS = [
  '/',
  '/index.html',
  '/css/app.css',
  '/js/app.js',
  '/images/icons/icon-192.png',
  '/images/icons/icon-512.png',
  '/manifest.json'
];

// Installation du Service Worker
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      console.log('📦 Cache créé:', CACHE_NAME);
      return cache.addAll(STATIC_ASSETS).catch(err => {
        console.warn('⚠️ Certains assets n\'ont pas pu être cachés:', err);
        // Ne pas échouer l'installation si quelques assets manquent
        return cache.addAll(STATIC_ASSETS.filter(url =>
          url.includes('manifest.json') || url.includes('index.html')
        ));
      });
    })
  );
  self.skipWaiting();
});

// Activation et nettoyage des anciens caches + notification de mise à jour
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName.startsWith(CACHE_PREFIX) && cacheName !== CACHE_NAME) {
            console.log('🗑️ Nettoyage du cache:', cacheName);
            // Notifier les clients qu'une nouvelle version est disponible
            self.clients.matchAll().then(clients => {
              clients.forEach(client => {
                client.postMessage({
                  type: 'NEW_VERSION_AVAILABLE',
                  message: 'Une nouvelle version du portfolio est disponible !'
                });
              });
            });
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Stratégie de cache : Network first, puis fallback sur le cache
self.addEventListener('fetch', event => {
  const { request } = event;
  const url = new URL(request.url);

  // Ignorer les requêtes non-GET et les domaines externes
  if (request.method !== 'GET' || url.origin !== self.location.origin) {
    return;
  }

  // Pour les APIs, utiliser Network First
  if (url.pathname.includes('/api/')) {
    event.respondWith(networkFirst(request));
    return;
  }

  // Pour les assets statiques, utiliser Cache First
  if (isStaticAsset(url)) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // Pour les autres requêtes, utiliser Stale While Revalidate
  event.respondWith(staleWhileRevalidate(request));
});

// Cache First : essayer le cache, puis le network
async function cacheFirst(request) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch (error) {
    console.error('❌ Erreur fetch:', error);
    // Retourner une page offline si disponible
    return caches.match('/offline.html') || new Response('Offline - Contenu non disponible');
  }
}

// Network First : essayer le network, puis le cache
async function networkFirst(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch (error) {
    console.warn('⚠️ Network failed, using cache:', request.url);
    const cached = await caches.match(request);
    return cached || new Response('Offline');
  }
}

// Stale While Revalidate : retourner le cache immédiatement, mettre à jour en arrière-plan
async function staleWhileRevalidate(request) {
  const cached = await caches.match(request);

  const fetchPromise = fetch(request).then(response => {
    if (response.ok) {
      const cache = caches.open(CACHE_NAME);
      cache.then(c => c.put(request, response.clone()));
    }
    return response;
  }).catch(() => cached || new Response('Offline'));

  return cached || fetchPromise;
}

// Vérifier si c'est un asset statique
function isStaticAsset(url) {
  return /\.(css|js|png|jpg|jpeg|svg|gif|webp|ico|eot|woff|woff2|ttf)$/i.test(url.pathname);
}

// Message depuis le client (pour les updates)
self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  if (event.data && event.data.type === 'CLEAR_CACHE') {
    caches.delete(CACHE_NAME);
    console.log('✅ Cache vidé');
  }
});
