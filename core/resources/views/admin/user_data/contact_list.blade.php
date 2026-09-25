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
                                    <th>@lang('List Name')</th>
                                    <th>@lang('Created At')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($contactLists as $contactList)
                                    <tr>
                                        <td>
                                          <x-admin.other.user_info :user="$contactList->user" />
                                        </td>
                                        <td>{{ __(@$contactList->name) }}</td>
                                        <td>
                                            <span class="badge badge--primary">{{ @$contactList->contact()->count() }}</span>
                                        </td>
                                        <td>
                                           <div>
                                                {{ showDateTime($contactList->created_at) }}<br>{{ diffForHumans($contactList->created_at) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($contactLists->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($contactLists) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
