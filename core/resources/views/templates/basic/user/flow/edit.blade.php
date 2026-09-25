@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div id="flow-builder" data-nodes="{{ $flow->nodes_json }}" data-keyword="{{ $flow->keyword }}"
            data-trigger="{{ $flow->trigger_type == Status::FLOW_TRIGGER_NEW_MESSAGE ? 'new_message' : 'keyword_match' }}"
            data-edges="{{ $flow->edges_json }}" data-id="{{ $flow->id }}" data-name="{{ $flow->name }}"></div>
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
