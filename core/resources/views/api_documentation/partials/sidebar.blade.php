<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="{{ siteLogo() }}" alt="logo">
        </div>
        <div class="sidebar-header__close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor"
                fill="none">
                <path d="M18 6L6.00081 17.9992M17.9992 18L6 6.00085" stroke="#141B34" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
    </div>

    <div class="sidebar-list">
        <div class="sidebar-item">
            <a href="#introduction" class="sidebar-list__header-subtitle doc-link active" data-target="introduction">
                @lang('Introduction')
            </a>
            <a href="#authentication" class="sidebar-list__header-subtitle doc-link" data-target="authentication">
                @lang('Authentication')
            </a>
            <a href="#response-format" class="sidebar-list__header-subtitle doc-link" data-target="response-format">
                @lang('Response Format')
            </a>
        </div>
        <div class="sidebar-item">
            <div class="sidebar-list__header">
                @lang('Contact')
            </div>

            <div class="sidebar-list__body">
                <a href="#contact-list" class="sidebar-list__item doc-link" data-target="contact-list">
                    <div class="sidebar-badge">
                        <span class="badge badge-success">GET</span>
                    </div>
                    <span class="sidebar-link">@lang('Contact List')</span>
                </a>

                <a href="#contact-store" class="sidebar-list__item doc-link" data-target="contact-store">
                    <div class="sidebar-badge">
                        <span class="badge badge-primary">POST</span>
                    </div>
                    <span class="sidebar-link">@lang('Create Contact')</span>
                </a>

                <a href="#contact-update" class="sidebar-list__item doc-link" data-target="contact-update">
                    <div class="sidebar-badge">
                        <span class="badge badge-primary">POST</span>
                    </div>
                    <span class="sidebar-link">@lang('Update Contact')</span>
                </a>

                <a href="#contact-delete" class="sidebar-list__item doc-link" data-target="contact-delete">
                    <div class="sidebar-badge">
                        <span class="badge badge-danger text-danger text-danger">DELETE</span>
                    </div>
                    <span class="sidebar-link">@lang('Delete Contact')</span>
                </a>

            </div>

        </div>



        <div class="sidebar-item">
            <div class="sidebar-list__header">
                @lang('Inbox')
            </div>
            <div class="sidebar-list__body">
                <a href="#conversation-list" class="sidebar-list__item doc-link" data-target="conversation-list">
                    <div class="sidebar-badge">
                        <span class="badge badge-success">GET</span>
                    </div>
                    <span class="sidebar-link">@lang('Conversation List')</span>
                </a>

                <a href="#conversation-messages" class="sidebar-list__item doc-link"
                    data-target="conversation-messages">
                    <div class="sidebar-badge">
                        <span class="badge badge-success">GET</span>
                    </div>
                    <span class="sidebar-link">@lang('Conversation Messages')</span>
                </a>


                <a href="#change-conversation-status" class="sidebar-list__item doc-link"
                    data-target="change-conversation-status">
                    <div class="sidebar-badge">
                        <span class="badge badge-primary">POST</span>
                    </div>
                    <span class="sidebar-link">@lang('Update Conversation Status')</span>
                </a>

                <a href="#conversation-details" class="sidebar-list__item doc-link" data-target="conversation-details">
                    <div class="sidebar-badge">
                        <span class="badge badge-success">GET</span>
                    </div>
                    <span class="sidebar-link">@lang('Conversation Details')</span>
                </a>

                <a href="#send-message" class="sidebar-list__item doc-link" data-target="send-message">
                    <div class="sidebar-badge">
                        <span class="badge badge-primary">POST</span>
                    </div>
                    <span class="sidebar-link">@lang('Send Message')</span>
                </a>

                <a href="#send-template-message" class="sidebar-list__item doc-link"
                    data-target="send-template-message">
                    <div class="sidebar-badge">
                        <span class="badge badge-primary">POST</span>
                    </div>
                    <span class="sidebar-link">@lang('Send Template Message')</span>
                </a>

                <a href="#get-template-list" class="sidebar-list__item doc-link" data-target="get-template-list">
                    <div class="sidebar-badge">
                        <span class="badge badge-success">GET</span>
                    </div>
                    <span class="sidebar-link">@lang('Get Template List')</span>
                </a>
            </div>
        </div>
    </div>
</aside>
