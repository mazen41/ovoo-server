<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four :url="route('admin.users.all')" variant="primary" title="Total Users" :value="$widget['all']"
            icon="las la-users" :currency=false />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four url="{{ route('admin.users.all') }}?date={{ now()->toDateString() }}" variant="danger"
            title="Users Joined Today" :value="$widget['today']" icon="las la-clock" :currency=false />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four
            url="{{ route('admin.users.all') }}?date={{ now()->subDays(7)->toDateString() }}to{{ now()->toDateString() }}"
            variant="success" title="Users Joined Last Week" :value="$widget['week']" icon="las la-calendar" :currency=false />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.four
            url="{{ route('admin.users.all') }}?date={{ now()->subDays(30)->toDateString() }}to{{ now()->toDateString() }}"
            variant="primary" title="Users Joined Last Month" :value="$widget['month']" icon="las la-calendar-plus"
            :currency=false />
    </div>
</div>
