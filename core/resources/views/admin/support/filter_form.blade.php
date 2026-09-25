@php
    $request = request();
@endphp

<form action="" id="filter-form" >
    <div class="form-group">
        <label class="form-label">@lang('Priority')</label>
        <select class="form-select select2" name="priority" data-minimum-results-for-search="-1">
            <option value="">@lang('All')</option>
            <option value="{{ Status::PRIORITY_HIGH }}"@selected($request->priority == Status::PRIORITY_HIGH)>@lang('High')</option>
            <option value="{{ Status::PRIORITY_MEDIUM }}"@selected($request->priority == Status::PRIORITY_MEDIUM)>@lang('Medium')</option>
            <option value="{{ Status::PRIORITY_LOW }}"@selected($request->priority == Status::PRIORITY_LOW)>@lang('Low')</option>
        </select>
    </div>
    @if ($request->routeIs('admin.ticket.index'))
        <div class="form-group">
            <label class="form-label">@lang('Status')</label>
            <select class="form-select select2" name="status" data-minimum-results-for-search="-1">
                <option value="">@lang('All')</option>
                <option value="{{ Status::TICKET_OPEN }}" @selected(Status::TICKET_OPEN == $request->status && !is_null($request->status))>@lang('Open')</option>
                <option value="{{ Status::TICKET_ANSWER }}"@selected($request->status == Status::TICKET_ANSWER)>@lang('Answer')</option>
                <option value="{{ Status::TICKET_REPLY }}"@selected($request->status == Status::TICKET_REPLY)>@lang('Reply')</option>
                <option value="{{ Status::TICKET_CLOSE }}"@selected($request->status == Status::TICKET_CLOSE)>@lang('Close')</option>
            </select>
        </div>
    @endif
    <x-admin.other.order_by />
    <x-admin.other.per_page_record />
    <x-admin.other.filter_dropdown_btn />
</form>
