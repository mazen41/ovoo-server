<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.withdraw.data.approved', request()->all()) }}"
            variant="success" title="Approved Withdrawal" :value="$widget['successful']" icon="las la-check-circle" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.withdraw.data.pending', request()->all()) }}"
            variant="warning" title="Pending Withdrawals" :value="$widget['pending']" icon="las la-spinner" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.withdraw.data.rejected', request()->all()) }}"
            variant="danger" title="Rejected Withdrawals" :value="$widget['rejected']" icon="las la-ban" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four
            url="{{ url()->route('admin.withdraw.data.pending', request()->all()) }}?date={{ now()->toDateString() }}"
            variant="primary" title="Today Withdrawal Request" :value="$widget['today_summary']" icon="las la-spinner" />
    </div>
</div>
