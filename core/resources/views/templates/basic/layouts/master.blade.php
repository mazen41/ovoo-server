@extends($activeTemplate . 'layouts.app')
@section('app-content')
    <div class="dashboard position-relative">
        <div class="dashboard__inner flex-wrap  @if (request()->routeIs('user.inbox.list')) chatbox-index-inner @endif " id="chatbox-index">
            @include('Template::partials.sidebar')
            <div class="dashboard__right">
                <div class="container-fluid p-0">
                    @include('Template::partials.auth_header')
                    @stack('topbar_tabs')
                    <div class="dashboard-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
