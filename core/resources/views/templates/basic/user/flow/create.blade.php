@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div id="flow-builder"></div>
    </div>
@endsection

@viteReactRefresh

@vite(['resources/js/flow_builder/app.jsx'])

@push('style')
    <style>
        .tooltip {
            z-index: 11 !important;
        }
    </style>
@endpush
