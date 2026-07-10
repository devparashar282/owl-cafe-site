const CACHE_NAME = 'owl-cafe-cache-v1';
const urlsToCache = [
  '/Cafe/index.php',
  '/Cafe/assets/css/style.css',
  '/Cafe/assets/css/responsive.css',
  '/Cafe/assets/js/main.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});
