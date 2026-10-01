@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout :renderExportButton="false">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Created At')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($contacts as $contact)
                                    <tr>
                                        <td>
                                          <x-admin.other.user_info :user="$contact->user" />
                                        </td>
                                        <td>{{ __($contact->fullName) }}</td>
                                        <td>{{ showMobileNumber($contact->mobileNumber) }}</td>
                                        <td>
                                            <div>
                                                {{ showDateTime($contact->created_at) }}<br>{{ diffForHumans($contact->created_at) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($contacts->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($contacts) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
