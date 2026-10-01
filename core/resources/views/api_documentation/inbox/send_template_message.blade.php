<div id="send-template-message" class="content__item">

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
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '{{ $externalAPiBaseURL }}/inbox/send-template-message',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('mobile_code' => '880','mobile' => 'xxxxxx','testmplate_id' => 'your template id'),
  CURLOPT_HTTPHEADER => array(
    'client-id: YOUR-CLIENT-ID',
    'client-secret: YOUR-CLIENT-SECRET',
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

</code></pre>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="content__inner">

        <div class="content-info intro">
            <h1 class="content-title">@lang('Send Template Message')</h1>
            <p class="content-desc">
                @lang('Send an approved WhatsApp template message to an existing conversation. Template messages are commonly used for notifications, alerts, and business-initiated communication.')
            </p>
        </div>

        <div class="content-info-api">
            <div class="content-info-badge badge badge-primary">POST</div>
            <div class="content-info-url">
                /external-api/inbox/send-template-message
            </div>
            <div class="content-info-copy">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"
                    color="currentColor" fill="none">
                    <path
                        d="M9 15C9 12.1716 9 10.7574 9.87868 9.87868C10.7574 9 12.1716 9 15 9L16 9C18.8284 9 20.2426 9 21.1213 9.87868C22 10.7574 22 12.1716 22 15V16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H15C12.1716 22 10.7574 22 9.87868 21.1213C9 20.2426 9 18.8284 9 16L9 15Z"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path
                        d="M16.9999 9C16.9975 6.04291 16.9528 4.51121 16.092 3.46243C15.9258 3.25989 15.7401 3.07418 15.5376 2.90796C14.4312 2 12.7875 2 9.5 2C6.21252 2 4.56878 2 3.46243 2.90796C3.25989 3.07417 3.07418 3.25989 2.90796 3.46243C2 4.56878 2 6.21252 2 9.5C2 12.7875 2 14.4312 2.90796 15.5376C3.07417 15.7401 3.25989 15.9258 3.46243 16.092C4.51121 16.9528 6.04291 16.9975 9 16.9999"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
        </div>

        {{-- Request Body --}}
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
                    <td><code>mobile_code</code></td>
                    <td>string</td>
                    <td>yes</td>
                    <td>@lang('Mobile country code. Must be a valid numeric country code without the plus (+) sign.')</td>
                </tr>
                <tr>
                    <td><code>mobile</code></td>
                    <td>string</td>
                    <td>yes</td>
                    <td>@lang('A valid mobile phone number associated with the provided country code.')</td>
                </tr>
                <tr>
                    <td><code>from_number</code></td>
                    <td>string</td>
                    <td>conditional</td>
                    <td>
                        @lang('A valid WhatsApp Business phone number registered on your account and in the Meta dashboard is required. If no ID is provided, the message will be sent using your default registered WhatsApp account.')
                    </td>
                </tr>
                <tr>
                    <td><code>template_id</code></td>
                    <td>integer</td>
                    <td>Yes</td>
                    <td>@lang('Approved WhatsApp template ID')</td>
                </tr>
            </tbody>
        </table>




        {{-- Notes --}}
        <h4 class="content-title">@lang('Notes')</h4>
        <p>@lang('Only approved WhatsApp templates can be sent.')</p>
        <p>@lang('Template messages are typically used for business-initiated conversations.')</p>
        <p>@lang('Blocked contacts cannot receive template messages.')</p>
        <p>@lang('WhatsApp account must be connected before sending messages.')</p>

    </div>
</div>