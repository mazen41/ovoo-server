@gs('secure_password')
    <div class="strong-password">
        <h6 class="title password-message">@lang('Password')</h6>
        <div class="progress-alert">
            <span class="item"></span>
            <span class="item"></span>
            <span class="item"></span>
            <span class="item"></span>
        </div>
        <ul class="list">
            <li class="item first-validation"><i class="las la-check-circle"></i> @lang('Minimum 6 characters')</li>
            <li class="item second-validation"><i class="las la-check-circle"></i> @lang('Uppercase and lowercase')</li>
            <li class="item third-validation"><i class="las la-check-circle"></i> @lang('At least one number')</li>
            <li class="item fourth-validation"><i class="las la-check-circle"></i> @lang('At least one symbol')</li>
        </ul>
    </div>

    @push('script')
        <script>
            "use strict";
            (function($) {

                const $strongPasswordWrapper = $(".strong-password");
                const $passwordInput = $('input[name=password]');
                $passwordInput.parent().addClass("is-strong-password")

                // When the password input gains focus, show the strong password wrapper and run the checker
                $passwordInput.on('focus', function() {
                    $strongPasswordWrapper.fadeIn();
                    strongPasswordChecker();
                });

                // When the user types in the password input, run the password strength checker
                $passwordInput.on('input', function() {
                    const password = $passwordInput.val();
                    strongPasswordChecker(password);
                });

                // When the password input loses focus, hide the strong password wrapper
                $passwordInput.on('blur', function() {
                    $strongPasswordWrapper.fadeOut();
                });

                //check the password strength  and show the message
                function strongPasswordChecker() {
                    let passwordStrength = 0;
                    let password = $passwordInput.val();

                    // Check minimum length
                    if (password.length >= 6) {
                        passwordStrength += 25;
                        $(".first-validation").addClass('text-success');
                    } else {
                        passwordStrength -= 25;
                        $(".first-validation").removeClass('text-success');
                    }

                    // Check for uppercase and lowercase
                    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                        passwordStrength += 25;
                        $(".second-validation").addClass('text-success');
                    } else {
                        passwordStrength -= 25;
                        $(".second-validation").removeClass('text-success');
                    }

                    // Check for at least one number
                    if (/\d/.test(password)) {
                        passwordStrength += 25;
                        $(".third-validation").addClass('text-success');
                    } else {
                        passwordStrength -= 25;
                        $(".third-validation").removeClass('text-success');
                    }

                    // Check for at least one symbol
                    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                        passwordStrength += 25;
                        $(".fourth-validation").addClass('text-success');
                    } else {
                        passwordStrength -= 25;
                        $(".fourth-validation").removeClass('text-success');
                    }

                    // Set password strength message
                    if (password.length >= 4) {
                        if (passwordStrength < 50) {
                            $(".password-message").text(`@lang('Weak Password')`);
                            $(".progress-alert span").removeClass('bg-warning bg-danger').addClass('bg-danger');
                        } else if (passwordStrength < 75) {
                            $(".password-message").text(`@lang('Good Password')`);
                            $(".progress-alert span").removeClass('bg-warning bg-danger').addClass('bg-warning');
                        } else {
                            $(".password-message").text(`@lang('Strong Password')`);
                            $(".progress-alert span").removeClass('bg-warning bg-danger').addClass('bg-success');
                        }
                    }
                };
            })(jQuery);
        </script>
    @endpush

    @push('style')
        <style>
            .is-strong-password {
                position: relative;
            }

            .strong-password {
                position: absolute;
                width: 250px;
                right: calc(100% + 10px);
                top: 60%;
                transform: translateY(-48%);
                background: #fff;
                box-shadow: 0 0 40px 0 rgba(29, 35, 58, .12);
                border: 1px solid #ebebeb;
                border-radius: 8px;
                padding: 15px;
                display: none;
                z-index: 3333;
            }

            .strong-password::after {
                content: "";
                position: absolute;
                pointer-events: none;
                right: -8px;
                top: 50%;
                transform: translateY(-50%);
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 8px 0 8px 8px;
                border-color: transparent transparent transparent #ffffff;
                z-index: 3;
            }

            .strong-password .title {
                font-size: 0.938rem;
                margin-bottom: 12px;
                color: hsl(var(--dark)) !important;
            }

            .strong-password .progress-alert {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                column-gap: 5px;
                margin-bottom: 15px;
            }

            .strong-password .progress-alert .item {
                height: 4px;
                border-radius: 4px;
                background-color: #444444;
            }

            .strong-password .list {
                list-style-type: none;
                padding: 0;
                margin-bottom: 0;
            }

            .strong-password .list .item {
                position: relative;
                margin-bottom: 8px;
                font-size: 0.875rem;
                font-weight: 500;
                color: #444444;
            }

            .strong-password .list .item:last-child {
                margin-bottom: 0;
            }

            .strong-password .list .item i {
                margin-right: 5px;
                color: inherit;
            }

            @media (max-width: 1199px) {
                .strong-password {
                    width: 225px;
                    padding: 12px;
                }

                .strong-password .list .item {
                    font-size: 0.813rem;
                    margin-bottom: 5px;
                }

                .strong-password .list .item i {
                    margin-right: 3px;
                }
            }

            @media (max-width: 991px) {
                .strong-password {
                    right: unset;
                    top: unset;
                    bottom: 100%;
                    left: 0;
                    transform: translateY(0);
                    transform: translateX(0);
                }

                .strong-password::after {
                    right: unset;
                    top: unset;
                    bottom: -8px;
                    left: 10%;
                    transform: translateY(0);
                    transform: translateX(-10%);
                    width: 0;
                    height: 0;
                    border-style: solid;
                    border-width: 8px 8px 0 8px;
                    border-color: #ffffff transparent transparent transparent;
                    z-index: 3;
                }

                .strong-password .title {
                    font-size: 0.875rem;
                    margin-bottom: 10px;
                }
            }

            @media (max-width: 767px) {
                .strong-password {
                    left: 0;
                    transform: translateX(0);
                }

                .strong-password::after {
                    right: unset;
                    top: unset;
                    left: 12%;
                    transform: translateX(-12%);
                }
            }

            @media (max-width: 575px) {
                .strong-password .progress-alert .item {
                    height: 2px;
                }
            }
        </style>
    @endpush
@endgs
