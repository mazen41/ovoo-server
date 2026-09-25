@extends('admin.layouts.app')
@section('panel')
    <x-admin.permission_check permission="view users">
        <x-admin.ui.widget.group.dashboard.users :widget="$widget" />
    </x-admin.permission_check>

    <x-admin.permission_check permission="view all transactions">
        <x-admin.ui.widget.group.dashboard.trx :widget="$widget" />
    </x-admin.permission_check>

    <x-admin.permission_check permission="view subscription history">
        <x-admin.ui.widget.group.dashboard.subscription_amount :widget="$widget" />
    </x-admin.permission_check>

    <x-admin.permission_check permission="view users">
        <x-admin.ui.widget.group.dashboard.users_more :widget="$widget" />
    </x-admin.permission_check>

    <x-admin.permission_check :permission="['view deposit', 'view withdraw']">
        <x-admin.ui.widget.group.dashboard.financial_overview :widget="$widget" />
    </x-admin.permission_check>

    <div class="row gy-4 mb-4">
        <x-admin.permission_check permission="view all transactions">
            <x-admin.other.dashboard_trx_chart />
        </x-admin.permission_check>
        <div class="col-xl-4">
            <x-admin.permission_check permission="view login history">
                <x-admin.other.dashboard_login_chart :userLogin=$userLogin />
            </x-admin.permission_check>
        </div>

    </div>
    <x-admin.other.cron_modal />
@endsection

@push('script-lib')
    <script src="{{ asset('assets/admin/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
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
