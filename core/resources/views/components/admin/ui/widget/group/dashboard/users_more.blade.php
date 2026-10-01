@props(['widget'])
<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.free')" variant="primary" title="Total Free User" :value="$widget['total_free_users']"
            icon="las la-user-check" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.subscribe')" variant="success" title="Total Subscribed Users" :value="$widget['total_subscribe_users']"
            icon="las la-users" />
    </div>

    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.subscribe.expired')" variant="warning" title="Total Subscription Expired User" :value="$widget['total_plan_expired_user']"
            icon="lar la-envelope" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.banned')" variant="danger" title="Total Ban User" :value="$widget['total_plan_ban_user']"
            icon="las la-comment-slash" />
    </div>
</div>
