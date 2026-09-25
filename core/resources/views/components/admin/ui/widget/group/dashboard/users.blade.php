@props(['widget'])
<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.all')" variant="primary" title="Total Users" :value="$widget['total_users']"
            icon="las la-users" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.active')" variant="success" title="Active User" :value="$widget['active_users']"
            icon="las la-user-check" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.email.unverified')" variant="warning" title="Email Unverified Users" :value="$widget['email_unverified_users']"
            icon="lar la-envelope" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.two :url="route('admin.users.mobile.unverified')" variant="danger" title="Mobile Unverified Users" :value="$widget['mobile_unverified_users']"
            icon="las la-comment-slash" />
    </div>
</div>
