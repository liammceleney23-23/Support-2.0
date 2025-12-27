const CACHE_NAME = 'it-support-pwa-v1';
const urlsToCache = [
    '/',
    '/index.php',
    '/view_tickets.php',
    '/styles.css',
    '/manifest.json',
    '/icon-192.svg',
    '/icon-512.svg'
];

// Install event - cache resources
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(urlsToCache))
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheName !== CACHE_NAME) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                return response || fetch(event.request);
            })
    );
});

// Push notification event
self.addEventListener('push', event => {
    let data = {
        title: 'IT Support Ticket Update',
        body: 'A ticket has been updated',
        icon: '/icon-192.svg',
        badge: '/icon-192.svg',
        tag: 'ticket-update',
        requireInteraction: true
    };

    if (event.data) {
        try {
            const payload = event.data.json();
            data = {
                title: payload.title || data.title,
                body: payload.body || data.body,
                icon: data.icon,
                badge: data.badge,
                tag: payload.ticket_id || data.tag,
                data: {
                    ticket_id: payload.ticket_id,
                    url: payload.url || '/view_tickets.php'
                },
                requireInteraction: true
            };
        } catch (e) {
            data.body = event.data.text();
        }
    }

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body: data.body,
            icon: data.icon,
            badge: data.badge,
            tag: data.tag,
            data: data.data,
            requireInteraction: data.requireInteraction,
            vibrate: [200, 100, 200]
        })
    );
});

// Notification click event
self.addEventListener('notificationclick', event => {
    event.notification.close();

    const urlToOpen = event.notification.data?.url || '/view_tickets.php';
    const ticketId = event.notification.data?.ticket_id;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(clientList => {
                // Check if there's already a window open
                for (let client of clientList) {
                    if (client.url.includes(urlToOpen) && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Open new window if no matching window found
                if (clients.openWindow) {
                    const finalUrl = ticketId ? `/manage_ticket.php?id=${ticketId}` : urlToOpen;
                    return clients.openWindow(finalUrl);
                }
            })
    );
});

// Background sync event (for offline ticket submissions)
self.addEventListener('sync', event => {
    if (event.tag === 'sync-tickets') {
        event.waitUntil(syncTickets());
    }
});

async function syncTickets() {
    // Placeholder for syncing tickets when back online
    console.log('Syncing tickets...');
}
