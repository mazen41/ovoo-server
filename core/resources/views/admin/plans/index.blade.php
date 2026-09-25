@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-12">
            <x-admin.ui.card class="table-has-filter">
                <x-admin.ui.card.body :paddingZero="true">
                    <x-admin.ui.table.layout :renderTableFilter="false">
                        <x-admin.ui.table>
                            <x-admin.ui.table.header>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Monthly Price')</th>
                                    <th>@lang('Yearly Price')</th>
                                    <th>@lang('Popular')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </x-admin.ui.table.header>
                            <x-admin.ui.table.body>
                                @forelse($pricingPlans as $pricingPlan)
                                    <tr>
                                        <td>{{ __(@$pricingPlan->name) }}</td>
                                        <td>{{ showAmount(@$pricingPlan->monthly_price) }}</td>
                                        <td>{{ showAmount(@$pricingPlan->yearly_price) }}</td>
                                        <td> @php echo $pricingPlan->popularBadge; @endphp </td>
                                        <td>
                                            @if (auth('admin')->user()->can('edit pricing plan'))
                                                <x-admin.other.status_switch :status="$pricingPlan->status" :action="route('admin.pricing.plan.status', $pricingPlan->id)"
                                                    title="pricing plan" />
                                            @else
                                                {!! $pricingPlan->statusBadge !!}
                                            @endif
                                        </td>
                                        <td>
                                            <div data-plan='@json($pricingPlan)'>
                                                <x-admin.permission_check permission="edit pricing plan">
                                                    <button class="btn btn-outline--primary table-action-btn editBtn">
                                                        <i class="las la-edit"></i> @lang('Edit')
                                                    </button>
                                                </x-admin.permission_check>
                                                <button type="button"
                                                    class="btn  btn-outline--info ms-1 table-action-btn detailsBtn">
                                                    <i class="las la-info-circle"></i> @lang('Details')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <x-admin.ui.table.empty_message />
                                @endforelse
                            </x-admin.ui.table.body>
                        </x-admin.ui.table>
                        @if ($pricingPlans->hasPages())
                            <x-admin.ui.table.footer>
                                {{ paginateLinks($pricingPlans) }}
                            </x-admin.ui.table.footer>
                        @endif
                    </x-admin.ui.table.layout>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>

    <x-admin.ui.modal id="planModal">
        <x-admin.ui.modal.header>
            <div>
                <h4 class="modal-title"></h4>
                <span class="d-block fs-14 modal-subtitle">
                    @lang('Add pricing plans based on your business needs. Use -1 in any limit field to allow unlimited access')
                </span>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form method="POST" class="row">
                @csrf
                <div class="form-group col-12 ">
                    <label>@lang('Name')</label>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group col-12">
                    <label>@lang('Short Description')</label>
                    <input type="text" step="any" class="form-control" name="description"
                        value="{{ old('description') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label class="form-label">@lang('Monthly Price')</label>
                    <div class="input-group input--group">
                        <input type="number" min="0" step="any" class="form-control" name="monthly_price"
                            value="{{ old('monthly_price') }}" required>
                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                    </div>
                </div>
                <div class="form-group col-lg-6">
                    <label class="form-label">@lang('Yearly Price')</label>
                    <div class="input-group input--group">
                        <input type="number" min="0" step="any" class="form-control" name="yearly_price"
                            value="{{ old('yearly_price') }}" required>
                        <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                    </div>
                </div>

                <div class="form-group col-lg-6">
                    <label>@lang('Whatsapp Account Limit')</label>
                    <input class="form-control" type="number" name="account_limit" min="-1"
                        value="{{ old('account_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Agent Limit')</label>
                    <input class="form-control" type="number" name="agent_limit" min="-1"
                        value="{{ old('agent_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Contact Limit')</label>
                    <input class="form-control" type="number" name="contact_limit" min="-1"
                        value="{{ old('contact_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Template Limit')</label>
                    <input class="form-control" type="number" name="template_limit"
                        min="-1"value="{{ old('template_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Automation Flow Limit')</label>
                    <input class="form-control" type="number" name="flow_limit" min="-1"
                        value="{{ old('flow_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Campaign Limit')</label>
                    <input class="form-control" type="number" name="campaign_limit" min="-1"
                        value="{{ old('campaign_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('ShortLink Limit')</label>
                    <input class="form-control" type="number" name="short_link_limit" min="-1"
                        value="{{ old('short_link_limit') }}" required>
                </div>
                <div class="form-group col-lg-6">
                    <label>@lang('Floater Limit')</label>
                    <input class="form-control" type="number" name="floater_limit" min="-1"
                        value="{{ old('floater_limit') }}" required>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="welcome_message">@lang('Welcome Message')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="welcome_message"
                                    name="welcome_message">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="is_popular">@lang('Is Popular')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_popular"
                                    name="is_popular">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="ai_assistance">@lang('AI Assistance')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="ai_assistance"
                                    name="ai_assistance">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="interactive_message">@lang('Interactive Message')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="interactive_message"
                                    name="interactive_message">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="ecommerce_available">@lang('E-commerce Available')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="ecommerce_available"
                                    name="ecommerce_available">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-4">
                    <div class="verification-switch">
                        <div class="verification-switch__item">
                            <label class="form-check-label fw-500" for="api_available">@lang('API Available')</label>
                            <div class="form-check form-switch form-switch-success form--switch pl-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="api_available"
                                    name="api_available">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-lg-12">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="detailModal">
        <x-admin.ui.modal.header>
            <div class="d-flex gap-1 flex-wrap align-items-center plan">
                <h4 class="modal-title">
                    @lang('Plan Details')
                </h4>
                <x-admin.permission_check permission="edit pricing plan">
                    <button class="text--primary editBtn plan" type="button" data-plan="">
                        <i class="las la-edit fs-18 ms-1"></i>
                    </button>
                </x-admin.permission_check>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <ul class="plan-details list-group list-group-flush">
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Name')</span>
                    <span class="name"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Description')</span>
                    <span class="description"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Monthly Price')</span>
                    <span class="monthly_price"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Yearly Price')</span>
                    <span class="yearly_price"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Whatsapp Account Limit')</span>
                    <span class="whatsapp_accounts"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Agent Limit')</span>
                    <span class="agent_limit"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Contact Limit')</span>
                    <span class="contact_limit"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Template Limit')</span>
                    <span class="template_limit"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Automation Flow Limit')</span>
                    <span class="flow_limit"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('ShortLink Limit')</span>
                    <span class="shortlink_limit"></span>
                </div>
                <div class="plan-details__item list-group-item">
                    <span class="item-title">@lang('Floater Limit')</span>
                    <span class="floater_limit"></span>
                </div>
                <div class="plan-details__item list-group-item border-0">
                    <span class="item-title">@lang('Welcome Message')</span>
                    <span class="welcome_message"></span>
                </div>
                <div class="plan-details__item list-group-item border-0">
                    <span class="item-title">@lang('AI Assistance')</span>
                    <span class="ai_assistance"></span>
                </div>
                <div class="plan-details__item list-group-item border-0">
                    <span class="item-title">@lang('Interactive Message')</span>
                    <span class="interactive_message"></span>
                </div>
            </ul>
        </x-admin.ui.modal.body>

    </x-admin.ui.modal>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <x-admin.permission_check permission="add pricing plan">
        <div class="d-flex flex-wrap gap-3 flex-fill">
            <button type="button" class="btn btn-outline--primary flex-fill addBtn">
                <i class="la la-plus"></i> @lang('New Plan')
            </button>
        </div>
    </x-admin.permission_check>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            const $createModal = $('#planModal');
            const $detailModal = $('#detailModal');

            $('.addBtn').on('click', function() {
                let action = "{{ route('admin.pricing.plan.store') }}";
                $createModal.find('form').trigger('reset');
                $createModal.find('form').attr('action', action);
                $createModal.find('.modal-title').text("@lang('Add New Plan')");
                $createModal.find('.modal-subtitle').text("@lang('Add pricing plans based on your business needs. Use -1 in any limit field to allow unlimited access.')");
                $createModal.modal('show');
            });

            $('.editBtn').on('click', function() {
                $detailModal.modal('hide');

                let plan = $(this).parent().data('plan');

                let route = "{{ route('admin.pricing.plan.update', ':id') }}";

                $createModal.find('form').attr('action', route.replace(':id', plan.id));
                $createModal.find('.modal-title').text("@lang('Edit Plan')");
                $createModal.find('.modal-subtitle').text("@lang('Edit pricing plans based on your business needs. Use -1 in any limit field to allow unlimited access.')");
                $createModal.find('input[name=name]').val(plan.name);
                $createModal.find('input[name=description]').val(plan.description);
                $createModal.find('input[name=monthly_price]').val(parseFloat(plan.monthly_price).toFixed(2));
                $createModal.find('input[name=yearly_price]').val(parseFloat(plan.yearly_price).toFixed(2));
                $createModal.find('input[name=account_limit]').val(plan.account_limit);
                $createModal.find('input[name=agent_limit]').val(plan.agent_limit);
                $createModal.find('input[name=contact_limit]').val(plan.contact_limit);
                $createModal.find('input[name=template_limit]').val(plan.template_limit);
                $createModal.find('input[name=campaign_limit]').val(plan.campaign_limit);
                $createModal.find('input[name=short_link_limit]').val(plan.short_link_limit);
                $createModal.find('input[name=floater_limit]').val(plan.floater_limit);
                $createModal.find('input[name=flow_limit]').val(plan.flow_limit);
                $createModal.find('input[name=welcome_message]').prop('checked', plan.welcome_message);
                $createModal.find('input[name=ai_assistance]').prop('checked', plan.ai_assistance);
                $createModal.find('input[name=interactive_message]').prop('checked', plan.interactive_message);
                $createModal.find('input[name=is_popular]').prop('checked', plan.is_popular);
                $createModal.find('input[name=ecommerce_available]').prop('checked', plan.ecommerce_available);
                $createModal.find('input[name=api_available]').prop('checked', plan.api_available);
                $createModal.modal('show');
            });

            $('.detailsBtn').on('click', function() {
                let plan = $(this).parent().data('plan');

                $detailModal.find('.plan').data('plan', plan);
                let listItem = $detailModal.find('.plan-details');
                listItem.find('.name').text(plan.name);
                listItem.find('.description').text(plan.description);
                listItem.find('.monthly_price').text(
                    `{{ gs('cur_sym') }}${getAmount(plan.monthly_price)}`);
                listItem.find('.yearly_price').text(
                    `{{ gs('cur_sym') }}${getAmount(plan.yearly_price)}`);
                listItem.find('.whatsapp_accounts').text(plan.account_limit);
                listItem.find('.agent_limit').text(plan.agent_limit);
                listItem.find('.contact_limit').text(plan.contact_limit);
                listItem.find('.template_limit').text(plan.template_limit);
                listItem.find('.flow_limit').text(plan.flow_limit);
                listItem.find('.shortlink_limit').text(plan.short_link_limit);
                listItem.find('.floater_limit').text(plan.floater_limit);



                if (plan.welcome_message) {
                    listItem.find('.welcome_message')
                        .html(`<span class="text--success">@lang('Yes')</span>`);

                } else {
                    listItem.find('.welcome_message')
                        .html(`<span class="text--danger">@lang('No')</span>`);
                }

                if (plan.ai_assistance) {
                    listItem.find('.ai_assistance')
                        .html(`<span class="text--success">@lang('Yes')</span>`);

                } else {
                    listItem.find('.ai_assistance')
                        .html(`<span class="text--danger">@lang('No')</span>`);
                }
                if (plan.interactive_message) {
                    listItem.find('.interactive_message')
                        .html(`<span class="text--success">@lang('Yes')</span>`);

                } else {
                    listItem.find('.interactive_message')
                        .html(`<span class="text--danger">@lang('No')</span>`);
                }

                $detailModal.modal('show');
            });

        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .verification-switch {
            grid-template-columns: unset;
        }

        .list-group-item {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding-left: 0;
        }
    </style>
@endpush
