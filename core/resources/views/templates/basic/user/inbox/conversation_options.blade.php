<x-permission_check permission="edit contact">
    <li>
        <a target="_blank" href="{{ route('user.contact.edit', @$conversation->contact->id) }}"
            class="text--info dropdown-item">
            <i class="las la-pen"></i>
            @lang('Edit')
        </a>
    </li>
</x-permission_check>

@if ($conversation->contact->is_blocked)
    <x-permission_check permission="unblock contact">
        <li>
            <button type="button" class="text--success confirmationBtn dropdown-item"
                data-action="{{ route('user.contact.unblock', $conversation->contact->id) }}?status=unblock"
                data-question="@if (@$conversation->contact?->blockedBy->is_agent) @lang('This contact was blocked by ') {{ @$conversation->contact?->blockedBy?->username }}. @endif @lang('Are you sure to unblock this contact?')">
                <i class="las la-check-circle"></i>
                @lang('Unblock')
            </button>
        </li>
    </x-permission_check>
@else
    <x-permission_check permission="block contact">
        <li>
            <button type="button" class="text--danger confirmationBtn dropdown-item"
                data-action="{{ route('user.contact.block', $conversation->contact->id) }}?status=block"
                data-question="@lang('Are you sure to block this contact?')">
                <i class="las la-ban"></i>
                @lang('Block')
            </button>
        </li>
    </x-permission_check>
@endif
<li>
    <button type="button" class="dropdown-item template_button">
        <i class="fa-regular fa-envelope"></i>
        @lang('Send Message Template')
    </button>
</li>
<li>
    <button type="button" class="dropdown-item clear_button confirmationBtn"
        data-action="{{ route('user.inbox.conversation.clear', $conversation->id) }}"
        data-question="@lang('Are you sure to clear this conversation?')">
        <i class="fa-regular fa-circle-xmark"></i> @lang('Clear Chat')
    </button>
</li>