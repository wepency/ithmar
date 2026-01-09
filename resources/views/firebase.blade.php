<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <title>Document</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://www.gstatic.com/firebasejs/7.9.3/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.9.3/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.9.3/firebase-messaging.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>

        const firebaseConfig = {
            apiKey: "AIzaSyCwNMNpTJ3jmqsfEKvLxQsGpNdj2a3xPL8",
            authDomain: "durrah-2a703.firebaseapp.com",
            projectId: "durrah-2a703",
            storageBucket: "durrah-2a703.appspot.com",
            messagingSenderId: "665609770858",
            appId: "1:665609770858:web:bcc28d6f83c50650b8f713",
            measurementId: "G-1289ETZ45Y"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    $.ajax({
                        url: '{{ route("save-token") }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token saved successfully.');
                        },
                        error: function (err) {
                            console.log('User Chat Token Error'+ err);
                        },
                    });
                }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
        }
        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });
    </script>
</head>
<body>
    <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
</body>
</html>
