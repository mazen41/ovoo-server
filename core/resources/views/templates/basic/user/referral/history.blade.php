@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="container-fluid p-0">
        <div class="referral-wrapper">
            <div class="row">
                <div class="col-xxl-5 col-lg-8">
                    <div class="referral-wrapper__top">
                        <h5 class="referral-wrapper__title">{{ __(@$pageTitle) }}</h5>
                        <p class="referral-wrapper__desc">
                            @lang('Invite your friends to')
                            <span class="text--bold"> {{ gs('site_name') }} </span>
                            @lang('and earn money for every successful referral.')
                        </p>
                    </div>
                    <div class="referral-card">
                        <p class="referral-card__title"> @lang('Refer Link') </p>
                        <div class="form-group">
                            <label class="form--label label-two mb-3">@lang('Share this link to invite others')</label>
                            <input type="text" class="form--control form-two referral-link"
                                value="{{ route('home', ['reference' => auth()->user()->username]) }}" readonly>
                        </div>
                        <button class="btn btn--white btn--sm copyBtn">
                            @lang('Copy link') <span class="btn-icon ms-1"> <i class="far fa-copy"></i> </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="flex-grow-1">
                    <div class="referral-item">
                        <div class="referral-item__top">
                            <h5 class="referral-item__title">@lang('Total Referrals')</h5>
                        </div>
                        <p class="referral-item__desc">{{ $widget['total_referrals'] }}</p>
                        <div class="referral-item__shape">
                            <img src="{{ getImage($activeTemplateTrue . 'images/rf-1.png') }}" alt="shape">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="referral-item">
                        <div class="referral-item__top">
                            <h5 class="referral-item__title">@lang('Successful Referrals')</h5>
                        </div>
                        <p class="referral-item__desc">{{ $widget['successful_referrals'] }}</p>
                        <div class="referral-item__shape">
                            <img src="{{ getImage($activeTemplateTrue . 'images/rf-2.png') }}" alt="shape">
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="referral-item">
                        <div class="referral-item__top">
                            <h5 class="referral-item__title">@lang('Total Earning')</h5>
                        </div>
                        <p class="referral-item__desc">{{ showAmount($widget['total_earning']) }}</p>
                        <div class="referral-item__shape">
                            <img src="{{ getImage($activeTemplateTrue . 'images/rf-3.png') }}" alt="shape">
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-table">
                <h5 class="dashboard-table__title">{{ __(@$pageTitle) }}</h5>
                <table class="table table--responsive--lg">
                    <thead>
                        <tr>
                            <th>@lang('Name')</th>
                            <th>@lang('Email')</th>
                            <th>@lang('Date')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($referrals as $referral)
                            <tr>
                                <td>{{ __(@$referral->fullName) }}</td>
                                <td>{{ showEmailAddress(@$referral->email) }}</a></td>
                                <td>{{ showDateTime(@$referral->created_at) }}</td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ @$referrals->links() }}
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function($) {
            "use strict";
            $('.copyBtn').on('click', function() {
                var copyText = $('.referral-link');
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(copyText.value);
                notify('success', "@lang('Link copied to clipboard')");
            });
        })(jQuery);
    </script>
@endpush
