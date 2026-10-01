@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-container">
        <div class="container-top">
            <div class="container-top__left">
                <h5 class="container-top__title"> @lang('We’re Here to Help') </h5>
                <p class="container-top__desc">@lang('Raise a support ticket to get expert help for your queries and concerns.')</p>
            </div>
            <div class="container-top__right">
                <div class="btn--group">
                    <a href="{{ route('ticket.open') }}" class="btn btn--base btn-shadow">
                        <i class="las la-plus"></i> @lang('Open New Ticket')
                    </a>
                </div>
            </div>
        </div>
        <div class="dashboard-container__body">
            <table class="table table--responsive--lg">
                <thead>
                    <tr>
                        <th>@lang('Subject')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Priority')</th>
                        <th>@lang('Last Reply')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supports as $support)
                        <tr>
                            <td>
                                <a href="{{ route('ticket.view', $support->ticket) }}"
                                    class="text-decoration-underline text-dark">
                                    [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a>
                            </td>
                            <td>
                                @php echo $support->statusBadge; @endphp
                            </td>
                            <td>
                                @php
                                    echo $support->priorityBadge;
                                @endphp
                            </td>
                            <td>{{ diffForHumans($support->last_reply) }} </td>

                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('ticket.view', $support->ticket) }}" class="action-btn text--info"
                                        data-bs-toggle="tooltip" data-bs-title="@lang('Details')">
                                        <i class="fas fa-desktop"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        @include('Template::partials.empty_message')
                    @endforelse
                </tbody>
            </table>
            {{ paginateLinks($supports) }}
        </div>
    </div>
@endsection

@push('topbar_tabs')
    @include('Template::partials.profile_tab')
@endpush
