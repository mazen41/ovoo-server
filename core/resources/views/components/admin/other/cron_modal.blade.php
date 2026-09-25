<x-admin.ui.modal id="cronModal">
    <x-admin.ui.modal.header class="p-3 p-md-4">
        <div>
            <h1 class="modal-title">@lang('Please Set Cron Job')</h1>
            <small>@lang('Once per 5-10 minutes is ideal while once every minute is the best option')</small>
        </div>
        <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
        </button>
    </x-admin.ui.modal.header>
    <x-admin.ui.modal.body class="p-3 p-md-4">
        <div class="mb-4">
            <div class="justify-content-between d-flex flex-wrap mb-2">
                <label class="fs-14">@lang('Cron Command')</label>
                <small class="fst-italic">
                    @lang('Last Cron Run'): <strong>{{ gs('last_cron') ? diffForHumans(gs('last_cron')) : 'N/A' }}</strong>
                </small>
            </div>
            <div class="input-group input--group">
                <input type="text" class="form-control" value="curl -s {{ route('cron') }}" readonly>
                <span class="input-group-text cursor-pointer copyBtn" data-copy="curl -s {{ route('cron') }}">
                    <i class="fas fa-copy me-1"></i>@lang('Copy')</span>
                </span>
            </div>
        </div>
        <div class="form-group">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.cron.index') }}" class="btn btn--success btn-large fs-14">
                    <i class="fas fa-cog"></i>
                    @lang('Cron Job Setting')
                </a>
                <a href="{{ route('cron') }}?target=all" class="btn btn--primary btn-large fs-14">
                    <i class="fas fa-bolt"></i>
                    @lang('Run Manually')
                </a>
            </div>
        </div>
    </x-admin.ui.modal.body>
</x-admin.ui.modal>

@push('script')
    <script>
        (function($) {
            "use strict";
            @php
                $lastCron = Carbon\Carbon::parse(gs('last_cron'))->diffInSeconds();
            @endphp

            @if ($lastCron >= 900)
                setTimeout(() => {
                    $('#cronModal').modal('show');
                }, 1000);
            @endif
        })(jQuery)
    </script>
@endpush
