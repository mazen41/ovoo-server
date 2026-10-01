<div class="table-export">
    <div class=" dropdown">
        <button class="btn btn-outline--secondary  dropdown-toggle w-100" type="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <span class="icon"> <i class="las  la-download"></i> </span>
            @lang('Export')
        </button>
        <div class="dropdown-menu">
            <ul class="table-export__list mb-0">
                <li class="table-export__item">
                    <a href="{{ appendQuery('export', 'excel') }}" class="table-export__link">
                        <span class="table-export__icon bg--success">
                            <i class="las la-file-excel"></i>
                        </span>
                        @lang('Excel')
                    </a>
                </li>
                <li class="table-export__item">
                    <a href="{{ appendQuery('export', 'csv') }}" class="table-export__link">
                        <span class="table-export__icon bg--primary"><i class="las la-file-csv"></i></span>
                        @lang('CSV')
                    </a>
                </li>
                <li class="table-export__item">
                    <a href="{{ appendQuery('export', 'pdf') }}" class="table-export__link">
                        <span class="table-export__icon bg--info"><i class="las la-file-pdf"></i></span>
                        @lang('PDF')
                    </a>
                </li>
                <li class="table-export__item">
                    <a target="_blank" href="{{ appendQuery('export', 'print') }}" class="table-export__link">
                        <span class="table-export__icon bg--warning"><i class="las la-print"></i></span>
                        @lang('Print')
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('style')
    <style>
        .table-export__icon {
            padding: 3px;
            color: #fff;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush
