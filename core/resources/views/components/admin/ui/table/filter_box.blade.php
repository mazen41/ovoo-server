<div class="table-filter">
    <div class=" dropdown">
        <button class="btn btn-outline--secondary w-100  dropdown-toggle" type="button" data-bs-toggle="dropdown"
            data-bs-auto-close="outside">
            <span class="icon">
                <i class="las la-sort"></i>
            </span>
            @lang('Filter')
        </button>
        <div class="dropdown-menu dropdown-menu-filter-box">
            @if (!$filterBoxLocation)
                @php
                    $request = request();
                @endphp
                <form action="" id="filter-form">
                    <x-admin.other.order_by />
                    <x-admin.other.per_page_record />
                    <x-admin.other.filter_dropdown_btn />
                </form>
            @else
                @include("admin.$filterBoxLocation")
            @endif
        </div>
    </div>
</div>
