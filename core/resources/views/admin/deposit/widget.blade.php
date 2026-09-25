<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.deposit.successful', request()->all()) }}" variant="success"
            title="Successful Deposit" :value="$widget['successful']" icon="las la-check-circle" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.deposit.pending', request()->all()) }}" variant="warning"
            title="Pending Deposit" :value="$widget['pending']" icon="las la-spinner" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.deposit.rejected', request()->all()) }}" variant="danger"
            title="Rejected Deposit" :value="$widget['rejected']" icon="las la-ban" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ url()->route('admin.deposit.initiated', request()->all()) }}" variant="primary"
            title="Initiated Deposit" :value="$widget['initiated']" icon="las la-money-check-alt" />
    </div>
</div>
