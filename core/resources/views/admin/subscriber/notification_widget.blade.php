<div class="col-sm-6">
    <x-admin.ui.widget.four url="javascript:void(0)" icon="fas fa-list" title="Email should be sent"
        value="{{ @$sessionData['total_subscriber'] }}" variant="primary" :currency=false />
</div>
<div class="col-sm-6">
    <x-admin.ui.widget.four url="javascript:void(0)" icon="fa-solid fa-envelope-circle-check" title="Email has been sent"
        value="{{ @$sessionData['total_sent'] }}" variant="success" :currency=false />
</div>
<div class="col-sm-6">
    <x-admin.ui.widget.four url="javascript:void(0)" icon="fa-solid fa-paper-plane" title="Email has yet to be sent"
        :currency=false value="{{ @$sessionData['total_subscriber'] - @$sessionData['total_sent'] }}"
        variant="warning" />
</div>
<div class="col-sm-6">
    <x-admin.ui.widget.four url="javascript:void(0)" icon="fas fa-envelope" title="Email per batch"
        value="{{ @$sessionData['batch'] }}" variant="primary" :currency=false />
</div>

<div class="col-12">
    <x-admin.ui.card>
        <x-admin.ui.card.body class="p-5 text-center">
            <div class="coaling-loader flex-column d-flex justify-content-center">
                <div class="countdown">
                    <div class="coaling-time">
                        <span class="coaling-time-count">{{ @$sessionData['cooling_time'] }}</span>
                    </div>
                    <div class="svg-count">
                        <svg viewBox="0 0 100 100">
                            <circle r="45" cx="50" cy="50" id="animate-circle"></circle>
                        </svg>
                    </div>
                </div>
                <p class="mt-2">
                    @lang("Email will be sent again with a") <span class="coaling-time-count"></span>
                    @lang(' second delay. Avoid closing or refreshing the browser.')
                </p>
                <p class="text--primary">
                    @php
                        $message =$sessionData['total_sent'] .' out of ' .$sessionData['total_subscriber'] .' email were successfully transmitted';
                    @endphp
                    {{ __($message) }}
                </p>
            </div>
        </x-admin.ui.card.body>
    </x-admin.ui.card>
</div>
