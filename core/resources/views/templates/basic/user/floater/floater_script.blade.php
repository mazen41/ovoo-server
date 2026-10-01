<script src="{{ asset('assets/global/js/whatsapp_floater.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        "use strict";
        whatsAppSetup({
            mobile: "{{ $floater->dial_code . $floater->mobile }}",
            message: @json($floater->message),
            color: "#{{ $floater->color_code }}"
        });
    });
</script>
