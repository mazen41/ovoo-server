<div class="form-group">
    <label class="form-label">@lang('Order By')</label>
    <select class="form-select select2" name="order_by" data-minimum-results-for-search="-1">
        <option value="desc">@lang('Latest')</option>
        <option value="asc" @selected(request()->order_by == 'asc')> @lang('Oldest')</option>
    </select>
</div>
