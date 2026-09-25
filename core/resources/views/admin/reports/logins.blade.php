@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout searchPlaceholder="Search Username" filterBoxLocation="reports.filter_form">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Login at')</th>
                                    <th>@lang('IP')</th>
                                    <th>@lang('Location')</th>
                                    <th>@lang('Browser | OS')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($loginLogs as $log)
                                    <tr>
                                        <td>
                                            <x-admin.other.user_info :user="$log->user" />
                                        </td>
                                        <td>
                                            {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                        </td>
                                        <td>
                                            <span class="fw-bold">
                                                <a
                                                    href="{{ route('admin.report.login.ipHistory', [$log->user_ip]) }}">{{ $log->user_ip }}</a>
                                            </span>
                                        </td>

                                        <td>{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                        <td>
                                            <div>
                                                <span class="d-block">
                                                    <i class="la la-{{ strtolower($log->browser) }}"></i>
                                                    {{ __($log->browser) }}
                                                </span>
                                                <span>
                                                    <i class="la la-{{ strtolower($log->os) }}"></i>
                                                    {{ __($log->os) }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($loginLogs->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($loginLogs) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection



@if (request()->routeIs('admin.report.login.ipHistory'))
    @push('breadcrumb-plugins')
        <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn  btn-outline--primary">
            <i class="las la-server"></i> @lang('Lookup IP') {{ $ip }}
        </a>
    @endpush
@endif
