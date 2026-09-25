@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout searchPlaceholder="Search subscribers">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Email')</th>
                                    <th>@lang('Subscribe At')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($subscribers as $subscriber)
                                    <tr>
                                        <td>{{ $subscriber->email }}</td>
                                        <td>{{ showDateTime($subscriber->created_at) }}</td>
                                        <td>
                                            <button class="btn  btn-outline--danger confirmationBtn"
                                                data-question="@lang('Are you sure to remove this subscriber?')"
                                                data-action="{{ route('admin.subscriber.remove', $subscriber->id) }}">
                                                <i class="fa-regular fa-trash-can"></i> @lang('Remove')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($subscribers->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($subscribers) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@if ($subscribers->count())
    @push('breadcrumb-plugins')
        <x-admin.permission_check permission="send user notification">
            <a href="{{ route('admin.subscriber.send.email') }}" class="btn  btn--primary">
                <i class="fa-regular fa-paper-plane"></i> @lang('Send Email')
            </a>
        </x-admin.permission_check>
    @endpush
@endif
