// Service Worker for SiReKa PWA
const CACHE_NAME = 'sireka-cache-v1';

self.addEventListener('install', (event) => {
    // Skip caching on install to ensure we always get fresh network responses
    // We only use the service worker to trigger the "Add to Home Screen" prompt for now.
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
    // Simple fetch strategy: Network first
    // For a highly dynamic app like SiReKa, we don't want to accidentally cache old data.
    event.respondWith(fetch(event.request));
});
