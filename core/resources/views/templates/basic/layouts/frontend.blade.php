@extends($activeTemplate . 'layouts.app')
@section('app-content')
    @stack('fbComment')
 


    <a class="scroll-top"><i class="fas fa-angle-double-up"></i></a>

    @include('Template::partials.header')
    @if (!request()->routeIs(['home', 'user.*']))
        @include('Template::partials.breadcrumb')
    @endif

    <main class="frontend">
        @yield('content')
    </main>
    @include('Template::partials.footer')
@endsection
