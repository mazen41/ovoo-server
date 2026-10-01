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
                                    <th>@lang('Tag')</th>
                                    <th>@lang('Contacts')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($contactTags as $contactTag)
                                    <tr>
                                        <td>
                                          <x-admin.other.user_info :user="$contactTag->user" />
                                        </td>
                                        <td>{{ __($contactTag->name) }}</td>
                                        <td><span class="badge badge--primary">{{ $contactTag->contacts_count }}</span></td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($contactTags->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($contactTags) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
