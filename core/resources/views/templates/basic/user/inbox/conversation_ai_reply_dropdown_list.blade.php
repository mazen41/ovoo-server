<button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="las la-robot chat-actions__icon me-1"></i> <span class="name">@lang('AI Reply')</span>
</button>
<ul class="dropdown-menu dropdown-menu-end chatbot-dropdown__menu">
    <li>
        <button type="button"
            class="dropdown-item ai-reply-button d-flex justify-content-between flex-wrap gap-2 align-items-center"
            data-value="{{ Status::YES }}">
            <span>@lang('Yes')</span>
            @if ($conversation->ai_reply == Status::YES)
                <i class="fa fa-check-double text--success"></i>
            @endif
        </button>
    </li>
    <li>
        <button type="button"
            class="dropdown-item ai-reply-button d-flex justify-content-between flex-wrap gap-2 align-items-center"
            data-value="{{ Status::NO }}">
            <span>@lang('No')</span>
            @if ($conversation->ai_reply == Status::NO)
                <i class="fa fa-check-double text--success"></i>
            @endif
        </button>
    </li>
</ul>
