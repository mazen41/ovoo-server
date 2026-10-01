<div id="contact-store" class="content__item">
    {{-- CURL Preview --}}
    <div class="content__preview">
        <div class="content__preview__inner">
            <div class="code-viewer">
                <div class="code-viewer__header">
                    <span class="code-viewer__header-title">php</span>
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
                    <div class="code-viewer__body-inner">
                        <pre><code class="language-php">
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '{{ $externalAPiBaseURL }}/contact/store',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('firstname' => 'John','lastname' => 'Doe','mobile_code' => '880','mobile' => '01988'),
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
    </div>

    {{-- Main Content --}}
    <div class="content__inner">

        <div class="content-info intro">
            <h1 class="content-title">@lang('Create New Contact')</h1>
            <p class="content-desc">
                This endpoint allows you to add a new contact to your {{ gs('site_name') }} account.
                Provide the necessary contact details, and upon successful request, the API returns
                the created contact’s information in JSON format for easy integration.
            </p>
        </div>

        <div class="content-info-api">
            <div class="content-info-badge badge badge-primary">POST</div>
            <div class="content-info-url">
                /contact/store
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

        <div>
            <h5>@lang('Required Fields')</h5>
            <p>
                @lang('The following fields are required to create a new contact in the system.')
            </p>
            <table class="doc-table">
                <thead>
                    <tr>
                        <th>@lang('Name')</th>
                        <th>@lang('Required')</th>
                        <th>@lang('Default')</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>@lang('firstname')</code></td>
                        <td>
                            <span class="badge bg-danger">@lang('Yes')</span>
                        </td>
                        <td>-</td>
                    </tr>

                    <tr>
                        <td><code>@lang('lastname')</code></td>

                        <td>
                            <span class="badge bg-danger">@lang('Yes')</span>
                        </td>
                        <td>-</td>
                    </tr>

                    <tr>
                        <td><code>@lang('mobile_code')</code></td>

                        <td>
                            <span class="badge bg-danger">@lang('Yes')</span>
                        </td>
                        <td>-</td>
                    </tr>

                    <tr>
                        <td><code>@lang('mobile')</code></td>

                        <td>
                            <span class="badge bg-danger">@lang('Yes')</span>
                        </td>
                        <td>-</td>
                    </tr>

                    <tr>
                        <td><code>@lang('city')</code></td>
                        <td>
                            <span class="badge bg-success">@lang('No')</span>
                        </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td><code>@lang('state')</code></td>
                        <td>
                            <span class="badge bg-success">@lang('No')</span>
                        </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td><code>@lang('post_code')</code></td>
                        <td>
                            <span class="badge bg-success">@lang('No')</span>
                        </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td><code>@lang('address')</code></td>
                        <td>
                            <span class="badge bg-success">@lang('No')</span>
                        </td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td><code>@lang('profile_image')</code></td>
                        <td>
                            <span class="badge bg-success">@lang('No')</span>
                        </td>
                        <td>-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
