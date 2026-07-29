const CACHE_NAME = 'owl-cafe-cache-v1';
const appBase = new URL('.', self.location.href);
const urlsToCache = [
  'index.php',
  'assets/css/style.css',
  'assets/css/responsive.css',
  'assets/js/main.js'
].map(path => new URL(path, appBase).href);

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
