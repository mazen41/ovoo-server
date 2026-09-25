<div class="input-group w-auto flex-fill ">
    <input name="date" type="search" class="date-picker form-control bg--white"
        placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
    <button class="btn btn--primary input-group-text"><i class="la la-search"></i></button>
</div>

@push('script-lib')
    <script src="{{ asset('assets/global/js/flatpickr.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            $(".date-picker").flatpickr({
                mode: 'range',
                maxDate: new Date(),
            });
        })(jQuery);
    </script>
@endpush
