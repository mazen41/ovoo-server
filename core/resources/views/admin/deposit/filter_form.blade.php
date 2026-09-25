@php
    $remarks = App\Models\Transaction::distinct('remark')->orderBy('remark')->get('remark');
@endphp
<form action="" id="filter-form">
    @if (request()->user_id)
        <input type="hidden" name="user_id" value="{{ request()->user_id }}">
    @endif
    <x-admin.other.filter_date />
    <x-admin.other.order_by />
    <x-admin.other.per_page_record />
    <x-admin.other.filter_dropdown_btn />
</form>
