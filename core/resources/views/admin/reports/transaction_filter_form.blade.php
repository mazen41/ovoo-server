@php
    $request = request();
    $remarks = App\Models\Transaction::distinct('remark')->orderBy('remark')->get('remark');
@endphp
<form action="" id="filter-form">
    @if ($request->user_id)
        <input type="hidden" name="user_id" value="{{ $request->user_id }}">
    @endif
    <div class="form-group">
        <label>@lang('Transaction Type')</label>
        <select name="trx_type" class="form-control select2" data-minimum-results-for-search="-1">
            <option value="">@lang('All')</option>
            <option value="+" @selected($request->trx_type == '+')>@lang('Plus')</option>
            <option value="-" @selected($request->trx_type == '-')>@lang('Minus')</option>
        </select>
    </div>
    <div class="form-group">
        <label>@lang('Remark')</label>
        <select class="form-control select2" data-minimum-results-for-search="-1" name="remark">
            <option value="">@lang('All')</option>
            @foreach ($remarks as $remark)
                <option value="{{ $remark->remark }}" @selected($request->remark == $remark->remark)>
                    {{ __(keyToTitle($remark->remark)) }}</option>
            @endforeach
        </select>
    </div>
    <x-admin.other.filter_date />
    <x-admin.other.order_by />
    <x-admin.other.per_page_record />
    <x-admin.other.filter_dropdown_btn />
</form>
