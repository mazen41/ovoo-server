@php
    $customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha();
@endphp

@props(['isAdmin' => false])

@if ($googleCaptcha)
    <div class="mb-3">
        @php echo $googleCaptcha @endphp
    </div>
@endif

@if ($customCaptcha)
    @if ($isAdmin)
        <div class="form-group custom-captcha">
            @php echo $customCaptcha @endphp
        </div>
        <div class="form-group">
            <label for="password" class="form--label">@lang('Captcha')</label>
            <div class="position-relative">
                <input id="captcha" name="captcha" required type="captcha" class="form--control h-48">
                <span class="password-show-hide fas toggle-password fa-eye-slash" id="#password"></span>
            </div>
        </div>
    @else
        <div class="form-group">
            <div class="mb-2">
                @php echo $customCaptcha @endphp
            </div>
            <div>
                <label class="form-label">@lang('Captcha')</label>
                <input type="text" placeholder="@lang('Enter the captcha')" name="captcha" class="form-control form--control" required>
            </div>
        </div>
    @endif

@endif
@if ($googleCaptcha)
    @push('script')
        <script>
            (function($) {
                "use strict"
                $('.verify-gcaptcha').on('submit', function() {
                    var response = grecaptcha.getResponse();
                    if (response.length == 0) {
                        document.getElementById('g-recaptcha-error').innerHTML =
                            '<span class="text--danger">@lang('Captcha field is required.')</span>';
                        return false;
                    }
                    return true;
                });

                window.verifyCaptcha = () => {
                    document.getElementById('g-recaptcha-error').innerHTML = '';
                }
            })(jQuery);
        </script>
    @endpush
@endif
