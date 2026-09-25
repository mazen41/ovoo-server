@extends('admin.layouts.app')
@section('panel')
    @include('admin.users.widget')
    <x-admin.ui.card class="table-has-filter">
        <x-admin.ui.card.body :paddingZero="true">
            <x-admin.ui.table.layout searchPlaceholder="Search users" filterBoxLocation="users.filter">
                <x-admin.ui.table>
                    <x-admin.ui.table.header>
                        <tr>
                            <th>@lang('User')</th>
                            @if (request()->routeIs('admin.users.agent'))
                                <th>@lang('Parent User')</th>
                            @else
                                <th>@lang('Purchased Plan')</th>
                            @endif
                            <th>@lang('Email-Mobile')</th>
                            <th>@lang('Country')</th>
                            <th>@lang('Joined At')</th>
                            <th>@lang('Balance')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </x-admin.ui.table.header>
                    <x-admin.ui.table.body>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <x-admin.other.user_info :user="$user" />
                                </td>
                                @if (request()->routeIs('admin.users.agent'))
                                    <td>
                                        <x-admin.other.user_info :user="$user->parent" />
                                    </td>
                                @else
                                    <td>
                                        {{ __(@$user->plan?->name ?? 'N/A') }}
                                    </td>
                                @endif
                                <td>
                                    <div>
                                        <strong class="d-block">
                                            {{ $user->email }}
                                        </strong>
                                        <small>{{ $user->mobileNumber }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-bold" title="{{ @$user->country_name }}">
                                            {{ $user->country_code }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="d-block ">{{ showDateTime($user->created_at) }}</strong>
                                        <small class="d-block"> {{ diffForHumans($user->created_at) }}</small>
                                    </div>
                                </td>
                                <td>{{ showAmount($user->balance) }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                        <a href="{{ route('admin.users.detail', $user->id) }}"
                                            class=" btn btn-outline--primary">
                                            <i class="las la-info-circle"></i>
                                            @lang('Details')
                                        </a>
                                        @if (request()->routeIs('admin.users.active'))
                                            <a href="{{ route('admin.users.agent') }}?parent_id={{ $user->id }}" class=" btn btn-outline--success">
                                                <i class="las la-users"></i>
                                                @lang('All Agent')
                                            </a>
                                        @endif
                                        @if (request()->routeIs('admin.users.kyc.pending'))
                                            <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank"
                                                class="btn btn-sm btn-outline--dark">
                                                <i class="las la-user-check"></i> @lang('KYC Data')
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <x-admin.ui.table.empty_message />
                        @endforelse
                    </x-admin.ui.table.body>
                </x-admin.ui.table>
                @if ($users->hasPages())
                    <x-admin.ui.table.footer>
                        {{ paginateLinks($users) }}
                    </x-admin.ui.table.footer>
                @endif
            </x-admin.ui.table.layout>
        </x-admin.ui.card.body>
    </x-admin.ui.card>
@endsection
