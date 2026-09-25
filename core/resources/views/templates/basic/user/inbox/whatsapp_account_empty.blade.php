@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="whatsapp-empty">
    <div class="thumb">
        <img src="{{ asset('assets/images/empty_account.png') }}" alt="image">
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex flex-column justify-content-center align-items-center h-100">
                <p class="text-center fs-14 fw-bold mb-2">@lang('You do not have a WhatsApp account. Please create one to proceed.')</p>
                <a href="{{ route('user.whatsapp.account.add') }}" class="btn btn--base btn-shadow"><i class="las la-plus"></i> @lang('Add Account')</a>
            </div>
        </div>
    </div>
</div>
@endsection
