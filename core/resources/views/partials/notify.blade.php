<link href="{{ asset('assets/global/css/iziToast.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/global/css/iziToast_custom.css') }}" rel="stylesheet">
<script src="{{ asset('assets/global/js/iziToast.min.js') }}"></script>

<script>
    "use strict";
    const colors = {
        success: '#28c76f',
        error: '#eb2222',
        warning: '#ff9f43',
        info: '#1e9ff2',
    }

    const icons = {
        success: 'fas fa-check',
        error: 'fas fa-times',
        warning: 'fas fa-exclamation',
        info: 'fas fa-info',
    }

    const notifications = @json(session('notify', []));
    const errors        = @json(@$errors ? collect($errors->all())->unique() : []);

    const triggerToaster = (status, message) => {
        iziToast[status]({
            title: status.charAt(0).toUpperCase() + status.slice(1),
            message: message,
            position: "topRight",
            backgroundColor: '#fff',
            icon: icons[status],
            iconColor: colors[status],
            progressBarColor: colors[status],
            titleSize: '14px',
            messageSize: '14px',
            titleColor: '#474747',
            messageColor: '#a2a2a2',
            transitionIn: 'obunceInLeft',
            timeout: 3000,
        });
    }

    if (notifications.length) {
        notifications.forEach(element => {
            triggerToaster(element[0], element[1]);
        });
    }

    if (errors.length) {
        errors.forEach(error => {
            triggerToaster('error', error);
        });
    }

    function notify(status, message) {
        if (typeof message == 'string') {
            triggerToaster(status, message);
        } else {
            $.each(message, (i, val) => triggerToaster(status, val));
        }
    }
</script>
