@php
    $request = request();
@endphp
<div class="form-group">
    <label class="form-label">@lang('Record to Display')</label>
    <select class="form-select select2" name="paginate" data-minimum-results-for-search="-1">
        <option value="20" @selected($request->paginate == 20)>@lang('20') @lang('Items')</option>
        <option value="40" @selected($request->paginate == 40)>@lang('40') @lang('Items')</option>
        <option value="60" @selected($request->paginate == 60)>@lang('60') @lang('Items')</option>
        <option value="80" @selected($request->paginate == 80)>@lang('80') @lang('Items')</option>
        <option value="100" @selected($request->paginate == 100)>@lang('100') @lang('Items')</option>
    </select>
</div>
