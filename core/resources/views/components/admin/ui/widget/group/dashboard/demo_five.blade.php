@props(['widget'])
<div class="row responsive-row">
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.one url="{{ route('admin.pricing.plan.index') }}" variant="primary" title="Total Plans"
            value="{{ @$widget['total_plans'] }}" :currency="false" icon="las la-gem" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.one url="{{ route('admin.pricing.plan.index') }}" variant="success" title="Active Plans"
            value="{{ @$widget['active_plans'] }}" :currency="false" icon="las la-bolt" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.one url="{{ route('admin.pricing.plan.index') }}" variant="success" title="Purchased Plans"
            value="{{ @$widget['purchased_plans'] }}" :currency="false" icon="las la-check-double" />
    </div>
    <div class="col-xxl-3 col-sm-6">
        <x-admin.ui.widget.one url="{{ route('admin.pricing.plan.index') }}" variant="info" title="Popular Plans"
            value="{{ @$widget['popular_plans'] }}" :currency="false" icon="las la-star" />
    </div>
</div>
