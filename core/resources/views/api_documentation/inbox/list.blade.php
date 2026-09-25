
    <div id="inbox-list" class="content__item">

        {{-- Preview (curl) --}}
        <div class="content__preview">
            <div class="content__preview__inner">
                <div class="code-viewer">
                    <div class="code-viewer__header">
                        <span class="code-viewer__header-title">php</span>
                        <button class="code-viewer__header-copy">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.25 5.25H7.25C6.14543 5.25 5.25 6.14543 5.25 7.25V14.25C5.25 15.3546 6.14543 16.25 7.25 16.25H14.25C15.3546 16.25 16.25 15.3546 16.25 14.25V7.25C16.25 6.14543 15.3546 5.25 14.25 5.25Z"
                                    stroke="currentColor" stroke-width="1.5"/>
                                <path
                                    d="M2.8 12L1.77 5.07C1.61 3.98 2.36 2.96 3.46 2.8L10.38 1.77"
                                    stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </button>
                    </div>

                    <div class="code-viewer__body">
<pre><code class="language-php">
$url = "https://api.yourdomain.com/external-api/inbox/list?contact_id=123&conversation=456";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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
                <h6 class="content-name">@lang('Conversation')</h6>
                <h1 class="content-title">@lang('Conversation List & Related Data')</h1>
                <p class="content-desc">
                    @lang('Fetch conversation details and related WhatsApp account info, CTA URLs, interactive lists, and templates for the authenticated user.')
                </p>
            </div>

            <div class="content-info-api">
                <div class="content-info-badge badge badge-success">GET</div>
                <div class="content-info-url">
                    /external-api/inbox/list
                </div>
            </div>

            <h4 class="content-title">@lang('Query Parameters')</h4>
            <table class="doc-table">
                <thead>
                <tr>
                    <th>@lang('Parameter')</th>
                    <th>@lang('Type')</th>
                    <th>@lang('Description')</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><code>contact_id</code></td>
                    <td>integer</td>
                    <td>@lang('Filter by contact ID to fetch or create conversation')</td>
                </tr>
                <tr>
                    <td><code>conversation</code></td>
                    <td>integer (optional)</td>
                    <td>@lang('Provide existing conversation ID to fetch data')</td>
                </tr>
                </tbody>
            </table>

            <h4 class="content-title">@lang('Sample Response')</h4>

            <div class="code-viewer">
                <div class="code-viewer__header">
                    <span class="code-viewer__header-title">JSON</span>
                </div>
                <div class="code-viewer__body">
<pre>
{
  "remark": "inbox",
  "status": "success",
  "message": ["Conversation data fetched successfully"],
  "data": {
    "conversation_id": 12,
    "conversation": {
      "id": 12,
      "user_id": 1,
      "contact_id": 123,
      "whatsapp_account_id": 2
    },
    "whatsapp_account": {
      "id": 2,
      "user_id": 1,
      "business_name": "Test Number",
      "phone_number": "+15556461105",
      "is_default": 1
    },
    "cta_urls": [
      {
        "id": 1,
        "name": "Info URL",
        "cta_url": "https://github.com/samim-tsk",
        "header_format": "IMAGE",
        "body": { "text": "This is the body text." },
        "action": { "parameters": { "display_text": "Visit Us", "url": "https://github.com/samim-tsk" } }
      }
    ],
    "interactive_lists": [
      {
        "id": 3,
        "name": "MyList1",
        "button_text": "Select",
        "header": { "type": "text", "text": "Welcome to our service" },
        "body": "Please choose an option from the list below.",
        "sections": [
          {
            "title": "Fruits",
            "rows": [
              { "id": "apple", "title": "Apple", "description": "Fresh red apples" },
              { "id": "banana", "title": "Banana", "description": "Sweet yellow banana" }
            ]
          }
        ]
      }
    ],
    "templates": [
      {
        "id": 8,
        "name": "promo_discount_offer_last",
        "body": "🎉 Unlock 25% Off Now! Hi {{1}}, use code LUCKY25.",
        "buttons": [
          { "type": "PHONE_NUMBER", "text": "Call Now", "phone_number": "+8801792938855" }
        ]
      }
    ]
  }
}
</pre>
                </div>
            </div>

            <h4 class="content-title">@lang('Notes')</h4>
            <p>
                @lang('Authentication is required. Use the') <code>Authorization: Bearer YOUR_API_TOKEN</code> @lang('header.').
            </p>
            <p>
                @lang('If') <code>contact_id</code> @lang('is provided, the conversation will be fetched or created automatically.').
            </p>
            <p>
                @lang('Response includes all related data: WhatsApp account, CTA URLs, interactive lists, and templates associated with the user.').
            </p>
        </div>
    </div>