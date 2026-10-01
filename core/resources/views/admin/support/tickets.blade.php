@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout searchPlaceholder="Search here..." filterBoxLocation="support.filter_form">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Submitted By')</th>
                                    <th>@lang('Subject')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Priority')</th>
                                    <th>@lang('Last Reply')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>
                                            @if ($ticket->user_id)
                                                <x-admin.other.user_info :user="$ticket->user" />
                                            @else
                                                <div
                                                    class="d-flex align-items-center gap-2 flex-wrap justify-content-end justify-content-md-start">
                                                    <span class="table-thumb">
                                                        <img src="{{ siteFavicon() }}" alt="user">
                                                    </span>
                                                    <span>{{ $ticket->name }}</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ticket.view', $ticket->id) }}" class="fw-smibold">
                                                [@lang('Ticket')#{{ $ticket->ticket }}]
                                                {{ strLimit($ticket->subject, 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            @php echo $ticket->statusBadge; @endphp
                                        </td>
                                        <td>
                                            @php echo $ticket->priorityBadge; @endphp
                                        </td>
                                        <td>
                                            <div>
                                                <span class="d-block">
                                                    {{ diffForHumans($ticket->last_reply) }}
                                                </span>
                                                <small>{{ showDateTime($ticket->last_reply) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.ticket.view', $ticket->id) }}"
                                                class="btn  btn-outline--primary">
                                                <i class="las la-info-circle"></i> @lang('Details')
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($tickets->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($tickets) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection
