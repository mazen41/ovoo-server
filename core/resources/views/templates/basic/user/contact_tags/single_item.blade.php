<tr>
    <td>{{ __(@$contactTag->name) }}</td>
    <td>
        <a href="{{ route('user.contact.list') }}?tag_id={{ @$contactTag->id }}">
            {{ $contactTag->contacts_count }}
        </a>
    </td>
    <td>
        <div class="action-buttons">
            <x-permission_check permission="edit contact tag">
                <button type="button" class="action-btn edit-btn" data-contact-tag='@json($contactTag)'
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="@lang('Edit')">
                    <i class="fas fa-pen"></i>
                </button>
            </x-permission_check>
            <x-permission_check permission="delete contact tag">
                <button type="button" class="action-btn delete-btn confirmationBtn" data-question="@lang('Are you sure to remove this contact tag?')"
                    data-action="{{ route('user.contacttag.delete', $contactTag->id) }}"data-bs-toggle="tooltip"
                    data-bs-placement="top" data-bs-title="@lang('Delete')">
                    <i class="fas fa-trash"></i>
                </button>
            </x-permission_check>
        </div>
    </td>
</tr>
