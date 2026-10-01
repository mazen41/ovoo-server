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

            var clearPermissionText = permission === 'denied' ?
                '@lang('If you previously blocked them, reset the permission from your browser settings and try again.')' :
                '@lang('To receive real-time push notifications, please allow notifications in your browser.')';

            // Append banner directly to body so it is always visible (no dependency on a .notice container)
            $('body').append(`
        <div class="notification-alert" style="position:fixed;top:18px;right:18px;z-index:99999;max-width:360px;background:#fff;border:1px solid #d1d5db;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.16);padding:16px 18px;font-family:inherit;color:#0f172a;">
            <a href="javascript:void(0);" class="notification-alert-close" style="position:absolute;top:8px;right:12px;text-decoration:none;color:#94a3b8;font-size:18px;line-height:1;">&times;</a>
            <div style="display:flex;align-items:flex-start;gap:10px;">
                <i class="las la-info-circle" style="font-size:22px;color:#2563eb;flex:none;"></i>
                <div style="flex:1;">
                    <h5 style="margin:0 0 4px;font-size:15px;font-weight:600;color:#0f172a;">@lang('Allow Browser Notifications')</h5>
                    <p style="margin:0 0 10px;font-size:13px;line-height:1.5;color:#475569;">${clearPermissionText}</p>
                    <a href="javascript:void(0);" class="allow-notification" style="display:inline-block;background:#2563eb;color:#fff;border-radius:6px;padding:7px 16px;font-size:13px;font-weight:600;text-decoration:none;">@lang('Allow Now')</a>
                </div>
            </div>
        </div>
        `);

            $('.notification-alert-close').on('click', function () {
                $('.notification-alert').remove();
            });
        }
    }


    $("body").on('click', ".allow-notification", function () {
        enablePushNotification()
    })
    // Only surface the permission banner/prompt on the inbox page, keeping other pages clean
    var showPushPrompt = @json(request()->routeIs('user.inbox.list'));

    // If push notification enabled from admin
    if (pushNotify == 1 && showPushPrompt) {
        pushNotifyAction();

        // Best-effort auto prompt when visiting the inbox (silently ignored if the browser requires a gesture; banner click remains as fallback)
        if (typeof Notification !== 'undefined' && Notification.permission === 'default') {
            setTimeout(function () {
                if (typeof messaging !== 'undefined' && messaging) {
                    enablePushNotification();
                }
            }, 800);
        }
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

                // Re-save the token automatically when Firebase rotates it
                messaging.onTokenRefresh(function () {
                    saveDeviceToken();
                });
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

    // Delete token function
    function deleteDeviceToken() {
        if (!messaging) {
            return;
        }
        messaging.getToken()
            .then(function (token) {
                if (!token) {
                    return;
                }
                $.ajax({
                    url: '{{ route("user.delete.device.token") }}',
                    type: 'POST',
                    data: {
                        token: token,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function () {
                        messaging.deleteToken()
                            .then(function () {
                                notify('success', 'Push notifications disabled successfully.');
                                $('.notification-alert').remove();
                            })
                            .catch(function () {
                                notify('error', 'Something went wrong while disabling push notifications.');
                            });
                    }
                });
            }).catch(function () { });
    }

    // Unsubscribe from push notifications
    $("body").on('click', ".disable-notification", function () {
        deleteDeviceToken();
    })

</script>