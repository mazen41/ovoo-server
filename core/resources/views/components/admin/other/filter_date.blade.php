@props(['label' => 'Date'])

<div class="form-group">
    <label class="form-label">{{ __($label) }}</label>
    <input name="date" type="search" class="date-picker form-control" placeholder="@lang('Start Date - End Date')" autocomplete="off"
        value="{{ request()->date ?? '' }}">
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
