importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyAn4GFuuaPzdOT4u8fw_lR-3lDiB7xG33k",
    authDomain: "bechakeena-dev.firebaseapp.com",
    projectId: "bechakeena-dev",
    storageBucket: "bechakeena-dev.firebasestorage.app",
    messagingSenderId: "781779507816",
    appId: "1:781779507816:web:be01475670c682abdd79dd",
    measurementId: "G-ZGBMBB9GWE"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/assets/images/logo/logo-icon.png' // optional    
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
