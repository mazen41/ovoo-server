<div id="note-store" class="content__item">

    {{-- Preview (curl) --}}
    <div class="content__preview">
        <div class="content__preview__inner">
            <div class="code-viewer">
                <div class="code-viewer__header">
                    <span class="code-viewer__header-title">php</span>
                    <button class="code-viewer__header-copy" title="Copy curl command">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.25 5.25H7.25C6.14543 5.25 5.25 6.14543 5.25 7.25V14.25C5.25 15.3546 6.14543 16.25 7.25 16.25H14.25C15.3546 16.25 16.25 15.3546 16.25 14.25V7.25C16.25 6.14543 15.3546 5.25 14.25 5.25Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M2.8 12L1.77 5.07C1.61 3.98 2.36 2.96 3.46 2.8L10.38 1.77" stroke="currentColor"
                                stroke-width="1.5" />
                        </svg>
                    </button>
                </div>

                <div class="code-viewer__body">
                    <pre><code class="language-php">
$url = "https://api.yourdomain.com/external-api/inbox/note-store";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);

$postFields = [
    'conversation_id' => 6,
    'note' => 'Customer requested a follow-up call tomorrow.',
];

curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Authorization: Bearer YOUR_API_TOKEN",
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Curl error: " . curl_error($ch);
}

curl_close($ch);

echo $response;
</code></pre>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content__inner">

        <div class="content-info intro">
            <h1 class="content-title">@lang('Store Conversation Note')</h1>
            <p class="content-desc">
                @lang('Add an internal note to a conversation for tracking, follow-ups, or internal communication.')
            </p>
        </div>

        <div class="content-info-api">
            <div class="content-info-badge badge badge-primary">POST</div>
            <div class="content-info-url">
                /external-api/inbox/note-store
            </div>
        </div>

        <h4 class="content-title">@lang('Request Body')</h4>
        <table class="doc-table">
            <thead>
                <tr>
                    <th>@lang('Field')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Required')</th>
                    <th>@lang('Description')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>conversation_id</code></td>
                    <td>integer</td>
                    <td>Yes</td>
                    <td>@lang('ID of the conversation where the note will be added')</td>
                </tr>
                <tr>
                    <td><code>note</code></td>
                    <td>string</td>
                    <td>Yes</td>
                    <td>@lang('Note content (maximum 255 characters)')</td>
                </tr>
            </tbody>
        </table>


        <h4 class="content-title">@lang('Notes')</h4>
        <p>
            @lang('Notes are internal and not visible to the contact.')
        </p>
        <p>
            @lang('Only conversations belonging to the authenticated user can receive notes.')
        </p>
        <p>
            @lang('Notes can later be retrieved via the conversation details endpoint.')
        </p>
    </div>
</div>