<script src="{{asset('assets/global/js/firebase/firebase-8.3.2.js')}}"></script>

<script>
    "use strict";

    var permission = null;
    var authenticated = '{{ auth()->user() ? true : false }}';
    var pushNotify = @json(gs('pn'));
    var firebaseConfig = @json(gs('firebase_config'));
    var messaging = null;

    // Show notice if permission not granted
    function pushNotifyAction() {

        permission = Notification.permission;

        if (!('Notification' in window)) {
            notify('info', 'Push notifications are not supported in your browser. Please use a Chromium-based browser.');
        }
        else if (permission === 'denied' || permission === 'default') {

            $('.notice').append(`
        <div class="alert notification-alert alert--info" role="alert">
            <div class="alert__content">
                <h4 class="alert__title">
                    <i class="las la-info-circle"></i> @lang("Allow or Reset Browser Notifications")
                </h4>
                <p class="mb-2 small">
                    @lang('To receive real-time push notifications, please allow notifications in your browser. If you previously blocked them, reset the permission from your browser settings and enable it again.') <a href="javascript:void(0);" class="text--base allow-notification">
                    <strong><i>@lang('Allow Now')</i></strong>
                </a>
                </p>

               
            </div>
        </div>
        `);
        }
    }


    $("body").on('click', ".allow-notification", function () {
        enablePushNotification()
    })
    // If push notification enabled from admin
    if (pushNotify == 1) {
        pushNotifyAction();
    }

    // Initialize Firebase
    if (firebaseConfig) {

        firebase.initializeApp(firebaseConfig);
        messaging = firebase.messaging();

        navigator.serviceWorker.register("{{ asset('assets/global/js/firebase/firebase-messaging-sw.js') }}")
            .then((registration) => {

                messaging.useServiceWorker(registration);

                // Receive message while page is open
                messaging.onMessage(function (payload) {

                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.data.icon,
                        image: payload.notification.image,
                        click_action: payload.data.click_action,
                        vibrate: [200, 100, 200]
                    };

                    new Notification(title, options);
                });

                // Auto register if already allowed
                if (Notification.permission === 'granted' && authenticated) {
                    saveDeviceToken();
                }

            });
    }

    // Request permission when user clicks button
    function enablePushNotification() {

        if (!messaging) {
            notify('error', 'Firebase messaging not initialized.');
            return;
        }

        messaging.requestPermission()
            .then(function () {
                return messaging.getToken();
            })
            .then(function (token) {

                $.ajax({
                    url: '{{ route("user.add.device.token") }}',
                    type: 'POST',
                    data: {
                        token: token,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function () {
                        notify('success', 'Push notification enabled successfully.');
                        $('.notification-alert').remove();
                    }
                });

            })
            .catch(function () {
                notify('error', 'Notification permission denied. Please enable it from your browser settings.');
            });
    }

    // Save token function
    function saveDeviceToken() {

        messaging.getToken()
            .then(function (token) {

                $.ajax({
                    url: '{{ route("user.add.device.token") }}',
                    type: 'POST',
                    data: {
                        token: token,
                        '_token': "{{ csrf_token() }}"
                    }
                    
                });

            }).catch(function () { });
    }

</script>