@forelse($users as $user)
    <tr>
        <td>
            <x-admin.other.user_info :user="$user" />
        </td>
        <td>
            <div>
                <strong class="d-block">
                    {{ $user->email }}
                </strong>
                <small>{{ $user->mobileNumber }}</small>
            </div>
        </td>
        <td>
            <div>
                <span class="fw-bold" title="{{ @$user->country_name }}">
                    {{ $user->country_code }}
                </span>
            </div>
        </td>
        <td>
            <div>
                <strong class="d-block ">{{ showDateTime($user->created_at) }}</strong>
                <small class="d-block"> {{ diffForHumans($user->created_at) }}</small>
            </div>
        </td>
        <td>{{ showAmount($user->balance) }}</td>
        <td>
            <div class="d-flex flex-wrap gap-2 justify-content-end">
                <a href="{{ route('admin.users.detail', $user->id) }}" class=" btn btn-outline--primary">
                    <i class="las la-info-circle"></i>
                    @lang('Details')
                </a>
                @if (request()->routeIs('admin.users.kyc.pending'))
                    <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank"
                        class="btn btn-sm btn-outline--dark">
                        <i class="las la-user-check"></i> @lang('KYC Data')
                    </a>
                @endif

            </div>
        </td>
    </tr>

@empty
    <x-admin.ui.table.empty_message />
@endforelse
