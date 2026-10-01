@props(['widget'])
<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four :url="route('admin.report.transaction')" variant="primary" title="Total Transaction" :value="$widget['total_trx']"
            icon="la la-exchange-alt" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        @php
            $trxPlusLink = route('admin.report.transaction') . '?trx_type=' . urlencode(' + ');
        @endphp
        <x-admin.ui.widget.four :url="$trxPlusLink" variant="success" title="Total Plus Transaction" :value="$widget['total_trx_plus']"
            icon="las la-plus" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four :url="route('admin.report.transaction') . '?trx_type=-'" variant="warning" title="Total Minus Transaction" :value="$widget['total_trx_minus']"
            icon="las la-minus-circle" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four :url="route('admin.report.transaction')" variant="primary" title="Total Transaction Count" :value="$widget['total_trx_count']"
            icon="la la-exchange-alt" :currency=false />
    </div>
</div>
