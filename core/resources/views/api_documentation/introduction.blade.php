@extends('api_documentation.layouts.master')
@section('master')
    <div id="introduction" class="content__item">
        <div class="content-info intro">
            <h1 class="content-title">Introduction</h1>
        </div>
        <div class="content__inner">
            <p>
                The {{ gs('site_name') }} API follows <b>RESTful</b> architecture standards, offering
                clear and consistent resource-based endpoints. All requests and responses are
                transmitted in JSON format, leveraging standard HTTP verbs, status codes, and
                authentication protocols to enable secure, efficient, and scalable integrations.
            </p>

            <h4 class="content-title">API Base URL</h4>
            <p class="content-desc">
                Please note that {{ gs('site_name') }} does not provide a sandbox or test environment. All API requests
                are processed in the live environment, so ensure that all request data and parameters
                are accurate before making any calls.
            </p>

            <div class="code-viewer">
                <div class="code-viewer__header">
                    <span class="code-viewer__header-title">string</span>
                    <button class="code-viewer__header-copy">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-gray-400 group-hover/copy-button:text-gray-500 dark:text-white/40 dark:group-hover/copy-button:text-white/60">
                            <path
                                d="M14.25 5.25H7.25C6.14543 5.25 5.25 6.14543 5.25 7.25V14.25C5.25 15.3546 6.14543 16.25 7.25 16.25H14.25C15.3546 16.25 16.25 15.3546 16.25 14.25V7.25C16.25 6.14543 15.3546 5.25 14.25 5.25Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path
                                d="M2.80103 11.998L1.77203 5.07397C1.61003 3.98097 2.36403 2.96397 3.45603 2.80197L10.38 1.77297C11.313 1.63397 12.19 2.16297 12.528 3.00097"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="code-viewer__body">
                    <pre>{{ route('home') }}/external-api</pre>
                </div>
            </div>
        </div>
    </div>

    <div id="authentication" class="content__item">
        <div class="content-info intro">
            <h1 class="content-title">Authentication</h1>
        </div>
        <div class="content__inner">
            <p>
                All requests to the {{ gs('site_name') }} API require authentication. Each API request must include a
                valid <strong>client-id</strong> and <strong>client-secret</strong> to the request header, which can be obtained
                from your {{ gs('site_name') }} Dashboard under <em>Developer Tools</em>.
            </p>

            <p>
                In addition to credentials, <strong>{{ gs('site_name') }} enforces IP-based security</strong>. You must
                register and enable
                your server’s public IP address in the <strong>IP Whitelist</strong> section of the dashboard.
                <strong>Requests originating from non-whitelisted IP addresses will be automatically rejected.</strong>
            </p>
            <p>
                <span class="text--danger">
                    <i>Both valid API credentials and an approved IP address are mandatory. Without completing
                        these two steps, authentication will fail and API access will not be granted.</i>
                </span>
            </p>
        </div>
    </div>

    <div id="response-format" class="content__item">
        <div class="content-info intro">
            <h1 class="content-title">Response Format</h1>
        </div>
        <div class="content__inner">
            <p>
                All responses from the {{ gs('site_name') }} API are returned in JSON format.
                Each response follows a consistent structure and includes a status indicator,
                message, and relevant data payload when applicable. Standard HTTP status codes
                are used to represent the outcome of each request.
            </p>
        </div>
        <h4 class="content-title">@lang('Sample Success Response')</h4>

        <div class="code-viewer">
            <div class="code-viewer__header">
                <span class="code-viewer__header-title">JSON</span>
            </div>
            <div class="code-viewer__body">
                <pre>
{
"status": "success",
"remark": "contact_list",
"message":[
    "Contact list fetched successfully"
],
"data": {
   ...you get all data here
    }
}
                    </pre>
            </div>
        </div>
        <h4 class="content-title">@lang('Error Sample Response')</h4>

        <div class="code-viewer">
            <div class="code-viewer__header">
                <span class="code-viewer__header-title">JSON</span>
            </div>
            <div class="code-viewer__body">
                <pre>
{
    "remark": "Unauthorized",
    "status": "error",
    "message": [
        "The client secret is required"
    ]
}
                    </pre>
            </div>
        </div>

        <div class="code-viewer">
            <div class="code-viewer__header">
                <span class="code-viewer__header-title">JSON</span>
            </div>
            <div class="code-viewer__body">
                <pre>
 {
    "remark": "Unauthorized",
    "status": "error",
    "message": [
        "Access to this API endpoint is restricted to IP addresses that have been explicitly whitelisted.",
        "In order to access this API endpoint, please add your IP address (::1) to the white list from the user dashboard."
    ]
}
                    </pre>
            </div>
        </div>
    </div>

    @include('api_documentation.pages_links')
@endsection
