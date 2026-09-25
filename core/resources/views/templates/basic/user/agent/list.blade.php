@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title">{{ __(@$pageTitle) }}</h5>
                <p class="container-top__desc">@lang('Organize and manage all your agents in one place with ease.')</p>
            </div>
            <x-permission_check permission="add agent">
                <div class="container-top__right">
                    <div class="btn--group">
                        <a href="{{ route('user.agent.create') }}" class="btn btn--base add-btn btn-shadow">
                            <i class="las la-plus"></i>
                            @lang('Add New Agent')
                        </a>
                    </div>
                </div>
            </x-permission_check>
        </div>
        <div class="dashboard-container__body">
            <div class="body-top">
                <div class="body-top__left">
                    <form class="search-form">
                        <input type="search" class="form--control form-two" placeholder="@lang('Search agent...')" name="search"
                            value="{{ request()->search }}">
                        <span class="search-form__icon"> <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                    </form>
                </div>
            </div>
            <div class="dashboard-table">
                <table class="table table--responsive--xl">
                    <thead>
                        <tr>
                            <th>@lang('Agent')</th>
                            <th>@lang('Email - Mobile')</th>
                            <th>@lang('Country')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($agents as $agent)
                            <tr>
                                <td>
                                    <div
                                        class="agent-info d-flex align-items-center gap-2 flex-wrap justify-content-end justify-content-md-start">
                                        <span class="table-thumb d-none d-lg-block">
                                            @if (@$agent->image)
                                                <img src="{{ $agent->image_src }}" alt="agent">
                                            @else
                                                <span class="name-short-form">
                                                    {{ __(@$agent->full_name_short_form ?? 'N/A') }}
                                                </span>
                                            @endif
                                        </span>
                                        <div>
                                            <strong class="d-block">
                                                {{ __(@$agent->fullname) }}
                                            </strong>
                                            <a class="agent-url" href="#">{{ @$agent->username }}</a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-block">
                                            {{ $agent->email }}
                                        </strong>
                                        <small>{{ $agent->mobileNumber }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold" title="{{ @$agent->country_name }}">
                                            {{ $agent->country_code }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-block ">{{ showDateTime($agent->created_at) }}</strong>
                                        <small class="d-block"> {{ diffForHumans($agent->created_at) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <x-permission_check permission="edit agent">
                                            <a href="{{ route('user.agent.edit', $agent->id) }}"
                                                class="action-btn text--base" data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Edit')">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="view permission">
                                            <a href="{{ route('user.agent.permissions', $agent->id) }}"
                                                class="action-btn text--info" data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Permissions')">
                                                <i class="fas fa-user-check"></i>
                                            </a>
                                        </x-permission_check>
                                        <x-permission_check permission="delete agent">
                                            <button type="button" class="action-btn confirmationBtn text-danger"
                                                data-question="@lang('Are you sure to delete this agent?')"
                                                data-action="{{ route('user.agent.delete', $agent->id) }}"data-bs-toggle="tooltip"
                                                data-bs-title="@lang('Delete')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </x-permission_check>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @include('Template::partials.empty_message')
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ paginateLinks($agents) }}
        </div>
    </div>
    <x-confirmation-modal isFrontend="true" />
@endsection
