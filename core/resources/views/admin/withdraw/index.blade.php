@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        @forelse($methods as $k=>$method)
            <div class="col-xl-4 col-sm-6 method-col">
                <x-admin.ui.card>
                    <x-admin.ui.card.body class="position-relative">
                        <div class="method-status">
                            <div class="form-check form-switch form--switch pl-0 form-switch-success justify-content-end">
                                <input class="form-check-input status-switch" type="checkbox" role="switch"
                                    @checked($method->status)
                                    data-action="{{ route('admin.withdraw.method.status', $method->id) }}"
                                    data-message-enable="@lang('Are you sure to enable this method?')" data-message-disable="@lang('Are you sure to disable this method?')">
                            </div>
                        </div>
                        <div class="flex-thumb-wrapper mb-3  align-items-center">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image, getFileSize('withdrawMethod')) }}"
                                    class="thumb-img">
                            </div>
                            <span class="ms-2 method-name">{{ __($method->name) }}</span>
                        </div>
                        <div class="mb-3">
                            <p>
                                @lang('This method supports ')
                                <span class="text--info">{{ __(@$method->currency) }}</span>
                                @lang('currency. With a fee structure of')
                                <span class="text--info">
                                    {{ showAmount($method->fixed_charge) }}
                                </span>
                                @lang('Plus')
                                <span class=" text--info">
                                    {{ 0 < $method->percent_charge ? ' + ' . getAmount($method->percent_charge) . '%' : '' }}
                                </span>
                                <span>.</span>
                                @lang('The transaction limits range from ')
                                <span class="text--info">
                                    {{ showAmount($method->min_limit) }}
                                </span>
                                @lang('to')
                                <span class=" text--info">
                                    {{ showAmount($method->max_limit) }}
                                </span>.

                            </p>
                        </div>
                        <a href="{{ route('admin.withdraw.method.edit', $method->id) }}"
                            class="btn  btn-outline--primary ms-1">
                            <i class="la la-pencil"></i>
                            @lang('Edit')
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
@endpush

@push('breadcrumb-plugins')
    <div class="d-flex gap-2  flex-wrap align-items-center">
        <div>
            <div class="input-group">
                <span class="input-group-text bg--white border-0">
                    <i class="las la-search"></i>
                </span>
                <input class="form-control bg--white highLightSearchInput border-0 ps-0" type="search"
                    placeholder="@lang('Search payment gateway')..." data-parent="method-col" data-search="method-name">
            </div>
        </div>
        <div>
            <a class="btn btn--primary adjust-input w-100" href="{{ route('admin.withdraw.method.create') }}">
                <i class="las la-plus"></i>
                @lang('Add New')
            </a>
        </div>
    </div>
@endpush



@push('style')
    <style>
        .flex-thumb-wrapper .thumb {
            width: 50px;
            height: 50px;
        }

        .method-status {
            position: absolute;
            right: 16px;
            top: 16px;
        }
    </style>
@endpush
