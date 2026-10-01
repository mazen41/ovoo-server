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
                                    <th>@lang('Response Type')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Created At')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($chatBots as $chatBot)
                                    <tr>
                                        <td>
                                          <x-admin.other.user_info :user="$chatBot->user" />
                                        </td>
                                        <td>{{ __($chatBot->title) }}</td>
                                        <td>{{ @$chatbot->response_type == Status::TEXT_RESPONSE ? 'Text' : 'Template' }}</td>
                                        <td>@php echo $chatBot->statusBadge; @endphp</td>
                                        <td>
                                            <div>
                                                {{ showDateTime($chatBot->created_at) }}<br>{{ diffForHumans($chatBot->created_at) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($chatBots->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($chatBots) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
