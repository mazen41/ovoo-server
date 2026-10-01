<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.six url="#" variant="danger" title="Plan Purchased Users"
            value="{{ @$widget['plan_purchased_users'] }}" icon="las la-arrow-down" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.six url="#" variant="warning" title="Plan Expired Users"
            value="{{ @$widget['plan_expired_users'] }}" icon="las la-arrow-down" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.six url="#" variant="primary" title="Total Subscriptions"
            value="{{ @$widget['total_subscriptions'] }}" icon="las la-arrow-down" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.six url="#" variant="success" title="Active Subscriptions"
            value="{{ @$widget['active_subscriptions'] }}" icon="las la-arrow-up" />
    </div>
</div>
