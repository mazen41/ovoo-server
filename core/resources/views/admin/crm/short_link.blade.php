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
                                    <th class="text-start">@lang('Short Link')</th>
                                    <th>@lang('Mobile')</th>
                                    <th>@lang('Click')</th>
                                    <th>@lang('Created At')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($shortLinks as $shortLink)
                                    <tr>
                                        <td>
                                          <x-admin.other.user_info :user="$shortLink->user" />
                                        </td>
                                        <td class="text-start">{{ route('home') }}/wl/{{ $shortLink->code }}</td>
                                        <td>{{ showMobileNumber($shortLink->mobileNumber) }}</td>
                                        <td><span class="badge badge--primary">{{ $shortLink->click }}</span></td>
                                        <td>
                                            <div>
                                                {{ showDateTime($shortLink->created_at) }}<br>{{ diffForHumans($shortLink->created_at) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($shortLinks->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($shortLinks) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
