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
                                    <th>@lang('Title')</th>
                                    <th>@lang('Total Message')</th>
                                    <th>@lang('Sent Message')</th>
                                    <th>@lang('Failed Message')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Campaign Date')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($campaigns as $campaign)
                                    <tr>
                                        <td>
                                            <x-admin.other.user_info :user="$campaign->user" />
                                        </td>
                                        <td>{{ __($campaign->title) }}</td>
                                        <td>{{ @$campaign->total_message }}</td>
                                        <td>{{ count(@$campaign->messages->where('status', Status::SENT)) }}</td>
                                        <td>{{ count(@$campaign->messages->where('status', Status::FAILED)) }}</td>
                                        <td>
                                            @php echo $campaign->statusBadge @endphp
                                        </td>
                                        <td>
                                            {{ showDateTime($campaign->send_at) }}<br>{{ diffForHumans($campaign->send_at) }}
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($campaigns->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($campaigns) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
