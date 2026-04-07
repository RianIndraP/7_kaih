// Import Firebase (VERSI COMPAT)
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js');

// Config Firebase
firebase.initializeApp({
    apiKey: "AIzaSyA-AM4wp75BPE6qO_qpCOBJhI5Al20MAJ0",
    authDomain: "kaih-96705.firebaseapp.com",
    projectId: "kaih-96705",
    storageBucket: "kaih-96705.firebasestorage.app",
    messagingSenderId: "483886147031",
    appId: "1:483886147031:web:50feb71270712893dc1792"
});

// Ambil messaging
const messaging = firebase.messaging();

// Handle notifikasi saat background
messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);

    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/img/logo-1.png'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});