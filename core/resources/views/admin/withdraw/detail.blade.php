@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-lg-4 col-md-6">
            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Withdraw Via') {{ __(@$withdrawal->method->name) }}</h4>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Date')</span>
                            <span class="fs-14">{{ showDateTime($withdrawal->created_at) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Trx Number')</span>
                            <span class="fs-14">{{ $withdrawal->trx }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Username')</span>
                            <span class="fs-14">
                                <a
                                    href="{{ route('admin.users.detail', $withdrawal->user_id) }}"><span>@</span>{{ @$withdrawal->user->username }}</a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Method')</span>
                            <span class="fs-14">{{ __($withdrawal->method->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Amount')</span>
                            <span class="fs-14 text--primary">{{ showAmount($withdrawal->amount) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Charge')</span>
                            <span class="fs-14 text--warning">{{ showAmount($withdrawal->charge) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('After Charge')</span>
                            <span class="fs-14 text--success">{{ showAmount($withdrawal->after_charge) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Rate')</span>
                            <span class="fs-14">1 {{ __(gs('cur_text')) }}
                                = {{ showAmount($withdrawal->rate) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Payable')</span>
                            <span class="fs-14 text--info">{{ showAmount($withdrawal->final_amount) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                            <span class="fs-14 text-muted">@lang('Status')</span>
                            <span class="text-end">
                                @php echo $withdrawal->statusBadge @endphp
                            </span>
                        </li>

                        @if ($withdrawal->admin_feedback)
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap ps-0">
                                <span class="fs-14 fw-500">@lang('Admin Response')</span>
                                <p class="fs-14">{{ $withdrawal->admin_feedback }}</p>
                            </li>
                        @endif
                    </ul>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
        <div class="col-lg-8 col-md-6">

            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('User Withdraw Information')</h4>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    @if ($details != null)
                        @foreach (json_decode($details) as $val)
                            <div class="mb-3">
                                <span class="fs-13 text-muted mb-1">{{ __($val->name) }}</span>
                                @if ($val->type == 'checkbox')
                                    {{ implode(',', $val->value) }}
                                @elseif($val->type == 'file')
                                    @if ($val->value)
                                        <br>
                                        <a
                                            href="{{ route('admin.download.attachment', encrypt(getFilePath('verify') . '/' . $val->value)) }}">
                                            <i class="fa-regular fa-file"></i> @lang('Attachment')
                                        </a>
                                    @else
                                        @lang('No File')
                                    @endif
                                @else
                                    <p class="fs-16">{{ __($val->value) }}</p>
                                @endif
                            </div>
                        @endforeach
                    @endif
                    @if ($withdrawal->status == Status::PAYMENT_PENDING)
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <x-admin.permission_check permission="approve withdraw">
                                <button class="btn btn-outline--success " data-bs-toggle="modal"
                                    data-bs-target="#approveModal">
                                    <i class="las la-check-double"></i> @lang('Approve')
                                </button>
                            </x-admin.permission_check>
                            <x-admin.permission_check permission="reject withdraw">
                                <button class="btn btn-outline--danger " data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                    <i class="las la-ban"></i> @lang('Reject')
                                </button>
                            </x-admin.permission_check>
                        </div>
                    @endif
                </x-admin.ui.card.body>
            </x-admin.ui.card>

        </div>
    </div>


    <x-admin.ui.modal id="approveModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Withdrawal Confirmation')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.withdraw.data.approve') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $withdrawal->id }}">
                <div class="form-group">
                    <p class="mb-2">
                        @lang('Have you sent')
                        <span class="fw-bold text--success">
                            {{ showAmount($withdrawal->final_amount, currencyFormat: false) }} {{ $withdrawal->currency }}
                        </span>?
                    </p>
                    <textarea name="details" class="form-control" value="{{ old('details') }}" rows="3"
                        placeholder="@lang('Provide the details. eg: transaction number')" required></textarea>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="rejectModal">
        <x-admin.ui.modal.header>
            <h1 class="modal-title">@lang('Reject Withdrawal Confirmation')</h1>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body>
            <form action="{{ route('admin.withdraw.data.reject') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $withdrawal->id }}">
                <div class="form-group">
                    <label>@lang('Reason of Rejection')</label>
                    <textarea name="details" class="form-control" rows="3" value="{{ old('details') }}" required></textarea>
                </div>
                <div class="form-group">
                    <x-admin.ui.btn.modal />
                </div>
            </form>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>
@endsection
