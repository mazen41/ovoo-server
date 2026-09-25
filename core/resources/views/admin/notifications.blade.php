@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-3">
        <div class="col-12">
            <ul class="notification-list">
                @forelse($notifications->groupBy('date') as $k=> $item)
                    <li class="notification-count mb-3">
                        {{ $k }}
                        <span class="badge badge--danger ms-2">{{ $item->count() }}</span>
                    </li>
                    @foreach ($item->sortBy('is_read') as $notification)
                        <li class="notification-item mb-3">
                            @if ($notification->user)
                                <div class="notification-item__thumb">
                                    @if ($notification->user->image)
                                        <img class="fit-image" src="{{ $notification->user->image_src }}">
                                    @else
                                        <span class="name-short-form">
                                            {{ __(@$user->full_name_short_form ?? 'N/A') }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="user-thumb">
                                    <img class="fit-image" src="{{ siteFavicon() }}">
                                </span>
                            @endif
                            <div class="notification-item__info">
                                <span
                                    class="@if ($notification->is_read) fw-500 @else fw-700 @endif notification-title me-2">
                                    {{ __($notification->title) }}
                                    <span class="time fs-12 ">
                                        {{ showDateTime($notification->created_at, gs('time_format')) }},
                                        {{ diffForHumans($notification->created_at) }}
                                    </span>
                                </span>
                                <small>
                                    @if ($notification->user)
                                        {{ __($notification->user->fullname) }}
                                    @else
                                        @lang('System')
                                    @endif
                                </small>
                            </div>
                            <div class="notification-item__action">
                                <a href="{{ route('admin.notification.read', $notification->id) }}"
                                    class="btn btn--success">
                                    <i class="fa-regular fa-eye"></i>
                                    @lang('View')
                                </a>
                                <button type="button" class="btn  btn--danger  confirmationBtn"
                                    data-question="@lang('Are you sure to delete the notification?')"
                                    data-action="{{ route('admin.notifications.delete.single', $notification->id) }}">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </li>
                    @endforeach
                @empty
                    <div class="li">
                        <x-admin.other.card_empty_message />
                    </div>
                @endforelse
            </ul>
        </div>
        @if ($notifications->hasPages())
            <div class="col-12">
                {{ paginateLinks($notifications) }}
            </div>
        @endif
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <div class="d-flex justify-content-between flex-wrap gap-2">
        @if ($hasUnread)
            <a href="{{ route('admin.notifications.read.all') }}" class="btn  btn-outline--primary flex-fill">
                <i class=" fa-regular fa-check-circle"></i> @lang('Mark All as Read')
            </a>
        @endif
        @if ($hasNotification)
            <button class="btn  btn-outline--danger confirmationBtn flex-fill"
                data-action="{{ route('admin.notifications.delete.all') }}" data-question="@lang('Are you sure to delete all notifications?')">
                <i class=" fa-regular fa-trash-alt"></i>
                @lang('Delete all Notification')
            </button>
        @endif
    </div>
@endpush

@push('style')
    <style>
        .user-thumb {
            width: 40px;
            height: 40px;
        }

        .user-thumb img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
@endpush
