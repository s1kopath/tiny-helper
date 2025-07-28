# 23. laravel fcm push

### usage > README_FIREBASE_SETUP.md


    <!-- index.html -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging-compat.js"></script>

    <script>
        const firebaseConfig = {
            apiKey: "your-app-key",
            authDomain: "your-project-id.firebaseapp.com",
            projectId: "your-project-id",
            storageBucket: "your-project-id.firebasestorage.app",
            messagingSenderId: "your-sender-id",
            appId: "your-app-id",
            measurementId: "your-measurement-id"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        // ✅ Request notification permission
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                // console.log("Notification permission granted.");

                messaging.getToken({
                        vapidKey: "BEBkth2o0diFoe5TVskBs6zMoQhgG83D7dbFabt9i__ckUY2PJF9vYzUb_0m6aNxgIKbjUGlkkBUNqogDKu4YjA", // From Firebase Console > Project Settings > Cloud Messaging > Web Push certificates
                    })
                    .then((currentToken) => {
                        if (currentToken) {
                            // console.log("FCM Token:", currentToken);

                            // Send token to Laravel backend via fetch/AJAX
                            fetch("{{ route('store.fcm.token') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                body: JSON.stringify({
                                    token: currentToken
                                }),
                            });
                        } else {
                            console.log("No registration token available.");
                        }
                    })
                    .catch((err) => {
                        console.log("An error occurred while retrieving token. ", err);
                    });
            } else {
                console.log("Notification permission not granted.");
            }
        });

        messaging.onMessage((payload) => {
            console.log('Message received. ', payload);
        });
    </script>
