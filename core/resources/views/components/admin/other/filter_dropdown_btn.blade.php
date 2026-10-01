<div class="gap-2 flex-align form-group mb-0">
    <button class="btn btn--secondary flex-fill js-close-dropdown btn-large gap-2 flex-center" type="button">
        <i class="fa-solid fa-xmark"></i> @lang('Close')
    </button>
    <button class="btn btn--primary gap-2 flex-center flex-fill btn-large" type="submit">
        <i class="fa-regular fa-paper-plane"></i> @lang('Apply')
    </button>
</div>

@push('script')
    <script>
        "use strict";
        (function($) {
            $('.js-close-dropdown').on('click', function(e) {
                $(this).closest(".dropdown-menu").dropdown('toggle');
            });
        })(jQuery);
    </script>
@endpush
