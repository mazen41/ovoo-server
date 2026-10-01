@props(['widget'])
<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ route('admin.user.subscriptions') }}" variant="primary" title="Total Subscription Amount" :value="$widget['total_subscription']"
            icon="las la-arrow-down" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ route('admin.user.subscriptions') }}?date={{ now()->toDateString() }}" variant="info" title="Today Subscription Amount" :value="$widget['today_subscription']" icon="las la-arrow-up" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ route('admin.user.subscriptions') }}?date={{ now()->subDays(7)->toDateString() }}to{{ now()->toDateString() }}" variant="warning" title="This Week Subscription Amount" :value="$widget['weekly_subscription']"
            icon="las la-arrow-down" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ route('admin.user.subscriptions') }}?date={{ now()->subDays(30)->toDateString() }}to{{ now()->toDateString() }}" variant="success" title="This Month Subscription Amount" :value="$widget['monthly_subscription']"
            icon="las la-arrow-down" />
    </div>
</div>
