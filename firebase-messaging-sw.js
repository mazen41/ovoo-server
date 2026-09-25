// 1. Import the Firebase scripts needed for the background worker
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.0/firebase-messaging-compat.js');

// 2. Your correct configuration block
const firebaseConfig = {
  apiKey: "AIzaSyAzxEifokVXzWKlXlhjKb8XTl6GuwuI3Qo",
  authDomain: "ovowpp-96407.firebaseapp.com",
  projectId: "ovowpp-96407",
  storageBucket: "ovowpp-96407.firebasestorage.app",
  messagingSenderId: "629217347779",
  appId: "1:629217347779:web:99bb785567d2f31a25ba76",
  measurementId: "G-4C4KZ7XJNP"
};

// 3. Initialize Firebase inside the service worker
firebase.initializeApp(firebaseConfig);

// 4. Retrieve Firebase Messaging
const messaging = firebase.messaging();

// 5. Handle background notifications
messaging.onBackgroundMessage(function(payload) {
    console.log('Received background message ', payload);
    
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.icon || '/firebase-logo.png'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});