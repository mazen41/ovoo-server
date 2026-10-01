/*
Give the service worker access to Firebase Messaging.
Note that you can only use Firebase Messaging here, other Firebase libraries are not available in the service worker.
*/
importScripts('firebase-app.js');
importScripts('firebase-messaging.js');

importScripts('configs.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
* New configuration for app@pulseservice.com
*/

firebase.initializeApp(firebaseConfig);

/*
Retrieve an instance of Firebase Messaging so that it can handle background messages.
*/
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
    console.log(
        "[firebase-messaging-sw.js] Received background message ",
        payload,
    );
    // Use the actual notification data pushed from the server
    const notificationTitle = (payload.notification && payload.notification.title) || 'New Message';
    const notificationOptions = {
        body: (payload.notification && payload.notification.body) || '',
        icon: (payload.data && payload.data.icon) || '/assets/global/images/logo-icon.png',
        image: (payload.notification && payload.notification.image) || '',
        click_action: (payload.data && payload.data.click_action) || '/',
        vibrate: [200, 100, 200],
        data: payload.data || {},
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});

// Open the correct URL when the user clicks the notification
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const url = (event.notification.data && event.notification.data.click_action) || event.notification.link || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(function(clientList) {
                for (const client of clientList) {
                    if ('focus' in client) {
                        client.navigate(url).then(function() {
                            client.focus();
                        });
                        return;
                    }
                }
                return clients.openWindow(url);
            })
    );
});