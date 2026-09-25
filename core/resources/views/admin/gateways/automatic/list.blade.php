@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @forelse($gateways->sortBy('alias') as $k=>$gateway)
            <div class="col-xl-4 col-xxl-3 col-sm-6 gateway-col">
                <x-admin.ui.card>
                    <x-admin.ui.card.body class="position-relative">
                        <div class="gateway-status">
                            <div class="form-check form-switch form--switch pl-0 form-switch-success justify-content-end">
                                <input class="form-check-input status-switch" type="checkbox" role="switch"
                                    @checked($gateway->status)
                                    data-action="{{ route('admin.gateway.automatic.status', $gateway->id) }}"
                                    data-message-enable="@lang('Are you sure to enable this gateway?')" data-message-disable="@lang('Are you sure to disable this gateway?')">
                            </div>
                        </div>
                        <div class="flex-thumb-wrapper mb-3  align-items-center">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('gateway') . '/' . $gateway->image, getFileSize('gateway')) }}"
                                    class="thumb-img">
                            </div>
                            <span class="ms-2 gateway-name">{{ __($gateway->name) }}</span>
                        </div>
                        <div class="mb-3">
                            <p>
                                <span class="fw-semibold">
                                    {{ $gateway->currencies->count() }}
                                </span>
                                @lang('out of')
                                <span class=" fw-semibold">{{ collect($gateway->supported_currencies)->count() }}</span>
                                @lang('supported currencies are currently activated for this gateway.')
                            </p>
                        </div>
                        <a href="{{ route('admin.gateway.automatic.edit', $gateway->alias) }}"
                            class="btn  btn-outline--primary">
                            <span class=" btn--icon"><i class="la la-tools"></i></span>@lang('Configure')
                        </a>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @endforeach
    </div>
    <div class="row responsive-row" id="manual-gateway">
        <div class="col-12">
            <div class="divider"></div>
            <div class=" d-flex justify-content-between flex-wrap gap-2">
                <p class="mb-0  fs-18  fw-semibold">@lang('Manual Gateway')</p>
                <a class="btn btn--primary" href="{{ route('admin.gateway.manual.create') }}">
                    <i class="las la-plus"></i> @lang('Add Manual Gateway')
                </a>
            </div>
        </div>
        @forelse($manualGateways as $k=>$mGateway)
            <div class="col-xl-4 col-xxl-3 col-sm-6 gateway-col">
                <x-admin.ui.card>
                    <x-admin.ui.card.body class="position-relative">
                        <div class="gateway-status">
                            <div class="form-check form-switch form--switch pl-0 form-switch-success justify-content-end">
                                <input class="form-check-input status-switch" type="checkbox" role="switch"
                                    @checked($mGateway->status)
                                    data-action="{{ route('admin.gateway.manual.status', $mGateway->id) }}"
                                    data-message-enable="@lang('Are you sure to enable this manual gateway?')" data-message-disable="@lang('Are you sure to disable this manual gateway?')">
                            </div>
                        </div>
                        <div class="flex-thumb-wrapper mb-3  align-items-center">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('gateway') . '/' . $mGateway->image, getFileSize('gateway')) }}"
                                    class="thumb-img">
                            </div>
                            <span class="ms-2 gateway-name">{{ __($mGateway->name) }}</span>
                        </div>
                        <div class="mb-3">
                            <p>
                                @lang('This manual gateway currency is ')
                                <strong>{{ __(@$mGateway->currencies->first()->currency) }}</strong>
                            </p>
                        </div>
                        <a href="{{ route('admin.gateway.manual.edit', $mGateway->alias) }}"
                            class="btn btn-outline--primary">
                            <span class="btn--icon"><i class="la la-tools"></i></span>@lang('Configure')
                        </a>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        @empty
            <div class="col-12">
                <x-admin.other.card_empty_message />
            </div>
        @endforelse
    </div>
    
    <x-confirmation-modal />
@endsection


@push('breadcrumb-plugins')
    <div class="d-flex gap-2  flex-wrap align-items-center">
        <div class="flex-fill">
            <div class="input-group ">
                <span class="input-group-text bg--white border-0">
                    <i class="las la-search"></i>
                </span>
                <input class="form-control bg--white highLightSearchInput border-0 ps-0" type="search"
                    placeholder="@lang('Search payment gateway')..." data-parent="gateway-col" data-search="gateway-name">
            </div>
        </div>
        <a class="btn btn--primary adjust-input" href="#manual-gateway">
            <i class="las la-university"></i> @lang('Manual Gateway')
        </a>
        <div class="flex-fill">
        </div>
    </div>
@endpush

@push('style')
    <style>
        .flex-thumb-wrapper .thumb {
            width: 50px;
            height: 50px;
        }

        .gateway-status {
            position: absolute;
            right: 16px;
            top: 16px;
        }

        .divider {
            position: relative;
            border-bottom: 1px solid hsl(var(--primary));
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .divider:before {
            position: absolute;
            content: '';
            width: 30px;
            height: 30px;
            border: 1px solid hsl(var(--primary));
            left: 50%;
            margin-left: -15px;
            top: 50%;
            background: #fff;
            margin-top: -15px;
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .divider:after {
            position: absolute;
            content: '';
            width: 20px;
            height: 20px;
            border: 1px solid hsl(var(--primary));
            left: 50%;
            margin-left: -10px;
            top: 50%;
            background: hsl(var(--primary));
            margin-top: -10px;
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
@endpush
