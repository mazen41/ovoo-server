@extends('admin.layouts.app')
@section('panel')
    <div class="row responsive-row">
        <div class="col-12">
            @if ($addons->isNotEmpty())
                <div class="marketplace__grid">
                    @foreach ($addons as $addon)
                        <div class="addon-card active">
                            <div class="addon-card__top">
                                <div class="addon-card__logo-wrapper addon-card__logo-wrapper--telegram">
                                    <img src="{{ addonAsset($addon->name, 'assets/images/logo.png') }}" alt="addon">
                                </div>
                                @if ($addon->status == Status::ADDON_INSTALLED)
                                    <div class="addon-card__status-badge addon-card__status-badge--enabled">
                                        <span class="addon-card__status-pulse"></span>
                                        @lang('Installed')
                                    </div>
                                @else
                                    <div class="addon-card__status-badge addon-card__status-badge--disabled">
                                        <span class="addon-card__status-pulse"></span>
                                        @lang('Uninstalled')
                                    </div>
                                @endif
                            </div>
                            <div class="addon-card__middle">
                                <h3 class="addon-card__name mb-2">{{ __($addon->name) }}</h3>
                                <p class="addon-card__title">{{ __($addon->title) }}</p>
                                <p class="addon-card__description">
                                    {{ __($addon->description) }}
                                </p>
                                @if ($addon->update_available)
                                    <button type="button" class="addon-update-notice update-addon-btn"
                                        data-addon-id="{{ $addon->id }}" data-addon-name="{{ $addon->name }}"
                                        data-current-version="{{ $addon->version }}"
                                        data-update-version="{{ $addon->update_available }}">
                                        <span class="addon-update-notice__icon">
                                            <i class="las la-arrow-up"></i>
                                        </span>
                                        <span class="addon-update-notice__content">
                                            <small>@lang('Update available')</small>
                                            <strong>v{{ $addon->update_available }}</strong>
                                        </span>
                                        <span class="addon-update-notice__action">
                                            @lang('Update now') <i class="las la-angle-right"></i>
                                        </span>
                                    </button>
                                @endif
                            </div>
                            <div class="addon-card__bottom">
                                <div class="addon-card__meta-info">
                                    <span class="addon-card__meta-label">@lang('Version')</span>
                                    <span class="addon-card__meta-value">v{{ __($addon->version) }}</span>
                                </div>
                                <div class="addon-card__price-info">
                                    <span class="addon-card__price-label">@lang('Status')</span>
                                    <span class="addon-card__price-value">
                                        <div class="d-flex justify-content-center">
                                            <div class="form-check form-switch form--switch pl-0 form-switch-success">
                                                <input class="form-check-input confirmationBtn" type="checkbox"
                                                    role="switch" @checked($addon->status)
                                                    data-action="{{ route('admin.addons.toggle', $addon->id) }}"
                                                    data-question="{{ $addon->status ? trans('Are you sure to uninstall this addon?') : trans('Are you sure to install this addon?') }}">
                                            </div>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="marketplace__empty">
                    <div class="marketplace__empty-icon">
                        <svg viewBox="0 0 100 100" class="marketplace__empty-svg">
                            <defs>
                                <linearGradient id="emptyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="hsl(var(--primary))" />
                                    <stop offset="100%" stop-color="hsl(var(--primary) / 0.4)" />
                                </linearGradient>
                                <filter id="emptyGlow" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur stdDeviation="6" result="blur" />
                                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                                </filter>
                            </defs>
                            <circle cx="50" cy="50" r="44" stroke="hsl(var(--border-color))"
                                stroke-width="1.2" stroke-dasharray="6 4" fill="none" />
                            <circle cx="50" cy="50" r="32" stroke="hsl(var(--border-color))" stroke-width="1"
                                stroke-dasharray="3 3" fill="none" />
                            <rect x="36" y="36" width="28" height="28" rx="8" fill="none"
                                stroke="url(#emptyGrad)" stroke-width="2.5" filter="url(#emptyGlow)" />
                            <path d="M46 43 L54 57 M54 43 L46 57" stroke="url(#emptyGrad)" stroke-width="2.5"
                                stroke-linecap="round" />
                            <circle cx="50" cy="50" r="3.5" fill="hsl(var(--bg-color))"
                                stroke="url(#emptyGrad)" stroke-width="2" />
                        </svg>
                    </div>
                    <h2 class="marketplace__empty-title">@lang('No Addons Activated')</h2>
                    <p class="marketplace__empty-description">
                        @lang("It looks like you don't have any active modules on your dashboard yet. Browse the official integration marketplace to synchronize services, automate workflows, and extend features.")
                    </p>
                    <button type="button" class="btn btn--primary btn-large install-btn">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0"
                                viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"
                                class="">
                                <g>
                                    <path
                                        d="M385 307.9h-62.2V167.7c0-16.7-13.5-30.2-30.2-30.2h-73.2c-16.7 0-30.2 13.6-30.2 30.2v140.2H127c-8 0-15.1 4.5-18.3 11.9-3.3 7.3-2 15.6 3.3 21.6L233.4 478c5.7 6.4 13.9 10.1 22.6 10.1 8.6 0 16.8-3.7 22.6-10.1L400 341.3c5.3-6 6.6-14.3 3.3-21.6S393 307.9 385 307.9zm7.5 26.8L271.1 471.3c-8 9-22.1 9-30.2 0L119.5 334.7c-5.8-6.5-1.2-16.8 7.6-16.8h72.2V167.7c0-11.1 9-20.2 20.2-20.2h73.2c11.2 0 20.2 9 20.2 20.2v150.2H385c8.7 0 13.3 10.3 7.5 16.8zm13.7-310.8H105.8c-16.6 0-30.2 13.5-30.2 30.2v35.3c0 16.7 13.6 30.2 30.2 30.2h300.4c16.6 0 30.2-13.6 30.2-30.2V54.1c0-16.6-13.5-30.2-30.2-30.2zm20.2 65.5c0 11.2-9 20.2-20.2 20.2H105.8c-11.2 0-20.2-9-20.2-20.2V54.1c0-11.1 9-20.2 20.2-20.2h300.4c11.1 0 20.2 9 20.2 20.2z"
                                        fill="#6338ff" opacity="1" data-original="#000000" class=""></path>
                                </g>
                            </svg>
                        </span> @lang('Begin Your Addon Journey')
                    </button>
                </div>
            @endif
        </div>
    </div>

    <x-admin.ui.modal id="install-modal">
        <x-admin.ui.modal.header class="install-form-content">
            <h4 class="modal-title">@lang('Install New Addon')</h4>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body class="install-form-content">
            <form action="{{ route('admin.addons.upload') }}" method="POST" enctype="multipart/form-data"
                class="upload-form no-submit-loader">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Purchase Code')</label>
                        <input type="text" name="purchase_code" class="form-control"
                            placeholder="xxxxxxxx - xxxx - xxxx - xxxx - xxxxxxxxxxxx " value="{{ old('purchase_code') }}"
                            required>
                        <small class="text-muted">@lang('You received after purchase.')</small>
                    </div>
                    <div class="form-group">
                        <label>@lang('Your Username')</label>
                        <input type="text" name="envato_username" class="form-control"
                            placeholder="@lang('Your Account Username')" value="{{ old('envato_username') }}" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Addon ZIP File')</label>
                        <input type="file" name="addon_zip" class="form-control" accept=".zip" required>
                        <small class="text-muted d-block mt-2">@lang('You received a zip file after purchase.')</small>
                    </div>
                    <div class="form-group">
                        <x-admin.ui.btn.modal />
                    </div>
                </div>

            </form>
        </x-admin.ui.modal.body>
        <x-admin.ui.modal.header class="install-progress-content d-none">
            <h4 class="modal-title">@lang('Installing Addon')</h4>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body class="install-progress-content d-none">
            <div class="addon-installation-progress" role="status" aria-live="polite">
                <div class="addon-installation-progress__loader" aria-hidden="true">
                    <span class="addon-installation-progress__ring"></span>
                    <span class="addon-installation-progress__ring addon-installation-progress__ring--inner"></span>
                    <i class="las la-box-open"></i>
                </div>
                <h5 class="addon-installation-progress__title">@lang('Setting up your addon')</h5>
                <p class="addon-installation-progress__description">
                    @lang('The package is being uploaded, verified, and installed. This may take a while.')
                </p>
                <div class="addon-installation-progress__bar" aria-hidden="true">
                    <span></span>
                </div>
                <p class="addon-installation-progress__note">
                    <i class="las la-info-circle"></i>
                    @lang('Please keep this window open until the installation is complete.')
                </p>
            </div>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>

    <x-admin.ui.modal id="update-addon-modal">
        <x-admin.ui.modal.header class="update-form-content">
            <div>
                <span class="addon-update-modal__eyebrow">@lang('Addon update')</span>
                <h4 class="modal-title mb-0">@lang('Upload Update Package')</h4>
            </div>
            <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">
                <i class="las la-times"></i>
            </button>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body class="update-form-content">
            <form action="{{ route('admin.addons.version.upload') }}" method="POST" enctype="multipart/form-data"
                class="addon-update-form no-submit-loader">
                @csrf
                <input type="hidden" name="addon_id" class="update-addon-id">

                <div class="addon-update-summary">
                    <div class="addon-update-summary__icon"><i class="las la-cube"></i></div>
                    <div class="addon-update-summary__details">
                        <small>@lang('You are updating')</small>
                        <strong class="update-addon-name"></strong>
                        <span>
                            <span class="update-current-version"></span>
                            <i class="las la-long-arrow-alt-right"></i>
                            <b class="update-target-version"></b>
                        </span>
                    </div>
                    <span class="addon-update-summary__badge">@lang('Update')</span>
                </div>

                <label class="addon-update-dropzone" for="addon-update-file" tabindex="0">
                    <input type="file" name="update_zip" id="addon-update-file" accept=".zip,application/zip"
                        required>
                    <span class="addon-update-dropzone__graphic">
                        <i class="las la-cloud-upload-alt"></i>
                    </span>
                    <strong>@lang('Drop the update ZIP here')</strong>
                    <span>@lang('or click to browse from your computer')</span>
                    <small class="addon-update-file-name">@lang('ZIP files only')</small>
                </label>

                <div class="addon-update-security-note">
                    <i class="las la-shield-alt"></i>
                    <span>@lang('The package slug and version will be verified before any update is allowed.')</span>
                </div>

                <button type="submit" class="btn btn--primary w-100 addon-update-submit">
                    <i class="las la-cloud-upload-alt"></i> @lang('Upload & Verify Update')
                </button>
            </form>
        </x-admin.ui.modal.body>
        <x-admin.ui.modal.header class="update-progress-content d-none">
            <h4 class="modal-title">@lang('Verifying Update')</h4>
        </x-admin.ui.modal.header>
        <x-admin.ui.modal.body class="update-progress-content d-none">
            <div class="addon-installation-progress" role="status" aria-live="polite">
                <div class="addon-installation-progress__loader" aria-hidden="true">
                    <span class="addon-installation-progress__ring"></span>
                    <span class="addon-installation-progress__ring addon-installation-progress__ring--inner"></span>
                    <i class="las la-shield-alt"></i>
                </div>
                <h5 class="addon-installation-progress__title">@lang('Checking your update package')</h5>
                <p class="addon-installation-progress__description">
                    @lang('The ZIP file is being uploaded while its addon identity and target version are securely verified.')
                </p>
                <div class="addon-installation-progress__bar" aria-hidden="true"><span></span></div>
                <p class="addon-installation-progress__note">
                    <i class="las la-info-circle"></i>
                    @lang('Please keep this window open until verification is complete.')
                </p>
            </div>
        </x-admin.ui.modal.body>
    </x-admin.ui.modal>


    <x-confirmation-modal />
@endsection


@if ($addons->isNotEmpty())
    @push('breadcrumb-plugins')
        <button class="btn btn--primary install-btn" type="button">
            <i class="las la-plus"></i> @lang('Install New Addon')
        </button>
    @endpush
@endif

@push('script')
    <script>
        "use strict";
        (function($) {
            const $modal = $("#install-modal");
            const $formContent = $modal.find('.install-form-content');
            const $progressContent = $modal.find('.install-progress-content');
            let isInstalling = false;

            $('.install-btn').on('click', function() {
                $modal.modal('show');
            });

            $modal.on('hide.bs.modal', function(e) {
                if (isInstalling) {
                    e.preventDefault();
                }
            });

            $(".upload-form").on('submit', function(e) {
                e.preventDefault();
                const $this = $(this);
                const $submitBtn = $this.find('button[type="submit"]');
                const oldHtml = $submitBtn.html();
                const formData = new FormData($this[0]);
                let installationSucceeded = false;

                $.ajax({
                    url: $this.attr('action'),
                    method: $this.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend() {
                        isInstalling = true;
                        $submitBtn.prop('disabled', true)
                            .addClass('disabled')
                            .text("@lang('Uploading')...");
                        $formContent.addClass('d-none');
                        $progressContent.removeClass('d-none');
                        $modal.attr('aria-busy', 'true');
                    },
                    success(response) {
                        notify(response.status, response.message);
                        if (response.status == 'success') {
                            installationSucceeded = true;
                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        }
                    },
                    error(e) {
                        const message = e.responseJSON?.message || '@lang('An unexpected error occurred. Please try again.')';
                        notify('error', message);
                    },
                    complete() {
                        $submitBtn.prop('disabled', false).removeClass('disabled').html(oldHtml);

                        if (!installationSucceeded) {
                            isInstalling = false;
                            $modal.removeAttr('aria-busy');
                            $progressContent.addClass('d-none');
                            $formContent.removeClass('d-none');
                        }
                    }
                });
            });

            const $updateModal = $('#update-addon-modal');
            const $updateFormContent = $updateModal.find('.update-form-content');
            const $updateProgressContent = $updateModal.find('.update-progress-content');
            const $updateForm = $updateModal.find('.addon-update-form');
            const $updateInput = $('#addon-update-file');
            const $dropzone = $updateModal.find('.addon-update-dropzone');
            const defaultFileLabel = "@lang('ZIP files only')";
            let isUpdating = false;

            function setUpdateFile(file) {
                if (!file || !file.name.toLowerCase().endsWith('.zip')) {
                    notify('error', "@lang('Please select a valid ZIP file.')");
                    $updateInput.val('');
                    $dropzone.removeClass('has-file');
                    $updateModal.find('.addon-update-file-name').text(defaultFileLabel);
                    return;
                }

                const transfer = new DataTransfer();
                transfer.items.add(file);
                $updateInput[0].files = transfer.files;
                $dropzone.addClass('has-file');
                $updateModal.find('.addon-update-file-name').text(file.name);
            }

            $('.update-addon-btn').on('click', function() {
                const $button = $(this);

                $updateForm.find('.update-addon-id').val($button.data('addon-id'));
                $updateModal.find('.update-addon-name').text($button.data('addon-name'));
                $updateModal.find('.update-current-version').text('v' + $button.data('current-version'));
                $updateModal.find('.update-target-version').text('v' + $button.data('update-version'));
                $updateModal.modal('show');
            });

            $dropzone.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $updateInput.trigger('click');
                }
            }).on('dragenter dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('is-dragging');
            }).on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('is-dragging');
            }).on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('is-dragging');
                setUpdateFile(e.originalEvent.dataTransfer.files[0]);
            });

            $updateInput.on('change', function() {
                setUpdateFile(this.files[0]);
            });

            $updateModal.on('hide.bs.modal', function(e) {
                if (isUpdating) {
                    e.preventDefault();
                }
            }).on('hidden.bs.modal', function() {
                $updateForm[0].reset();
                $dropzone.removeClass('has-file is-dragging');
                $updateModal.find('.addon-update-file-name').text(defaultFileLabel);
                $updateProgressContent.addClass('d-none');
                $updateFormContent.removeClass('d-none');
                $updateModal.removeAttr('aria-busy');
            });

            $updateForm.on('submit', function(e) {
                e.preventDefault();
                const $submitBtn = $(this).find('button[type="submit"]');
                const oldHtml = $submitBtn.html();
                let updateSucceeded = false;

                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    beforeSend() {
                        isUpdating = true;
                        $submitBtn.prop('disabled', true);
                        $updateFormContent.addClass('d-none');
                        $updateProgressContent.removeClass('d-none');
                        $updateModal.attr('aria-busy', 'true');
                    },
                    success(response) {
                        notify(response.status, response.message);

                        if (response.status == 'success') {
                            updateSucceeded = true;
                            setTimeout(() => {
                                isUpdating = false;
                                $updateModal.one('hidden.bs.modal', () => location.reload());
                                $updateModal.modal('hide');
                            }, 1500);
                        }
                    },
                    error(e) {
                        const message = e.responseJSON?.message || "@lang('The update package could not be verified.')";
                        notify('error', message);
                    },
                    complete() {
                        $submitBtn.prop('disabled', false).html(oldHtml);

                        if (!updateSucceeded) {
                            isUpdating = false;
                            $updateModal.removeAttr('aria-busy');
                            $updateProgressContent.addClass('d-none');
                            $updateFormContent.removeClass('d-none');
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush




@push('style')
    <style>
        /* Grid Element */
        .marketplace__grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 2rem;
            align-items: stretch;
        }

        .addon-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background-color: hsla(var(--addon-card-bg) / 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px dashed hsl(var(--border-color));
            border-radius: 20px;
            padding: 1.8rem;
            box-shadow: var(--addon-card-shadow);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) box-shadow 0.3s cubic-bezier(0.2, 0.8, 0.2, 1),
                border-color 0.3s ease;
        }

        /* Subtle background accent glow */
        .addon-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, hsl(var(--primary) / 0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        /* Premium Hover Lift Effect */
        .addon-card:hover,
        .addon-card.active {
            transform: translateY(-4px);
            box-shadow: var(--addon-card-shadow-hover);
            border-color: var(--addon-card-border-hover);
        }

        .addon-card:hover::before {
            opacity: 1;
        }

        /* Card Modifier: addon-card--disabled */
        .addon-card--disabled {
            opacity: 0.93;
        }

        .addon-card--disabled .addon-card__logo-wrapper {
            filter: grayscale(40%) opacity(75%);
        }

        .addon-card--disabled .addon-card__name {
            opacity: 0.85;
        }

        .addon-card--disabled .addon-card__description {
            opacity: 0.7;
        }

        /* Card Element: top */
        .addon-card__top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        /* Logo container element */
        .addon-card__logo-wrapper {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: hsl(var(--secondary));
            box-shadow: 0 4px 12px -3px rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .addon-card__logo {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .addon-card__logo-wrapper::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 60%);
            pointer-events: none;
        }

        /* Brand Modifiers for Logo Wrapper */
        .addon-card__logo-wrapper--telegram {
            background-color: hsla(200, 80%, 50%, 0.08);
        }

        .addon-card__logo-wrapper--stripe {
            background-color: hsla(250, 100%, 65%, 0.08);
        }

        .addon-card__logo-wrapper--ai {
            background-color: hsla(320, 85%, 60%, 0.08);
        }

        .addon-card__logo-wrapper--seo {
            background-color: hsla(160, 84%, 39%, 0.08);
        }

        .addon-card__logo-wrapper--analytics {
            background-color: hsla(38, 92%, 50%, 0.08);
        }

        .addon-card__logo-wrapper--theme {
            background-color: hsla(190, 90%, 42%, 0.08);
        }

        /* Status Badge element */
        .addon-card__status-badge {
            font-family: var(--font-body);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            line-height: 1;
        }

        /* Status Badge Modifier: addon-card__status-badge--enabled */
        .addon-card__status-badge--enabled {
            background-color: hsla(var(--success) / 0.08);
            color: hsl(var(--success));
            border: 1px solid hsla(var(--success) / 0.15);
            box-shadow: 0 0 12px hsla(var(--success) / 0.05);
        }

        .addon-card__status-pulse {
            width: 6px;
            height: 6px;
            background-color: hsl(var(--success));
            border-radius: 50%;
            display: inline-block;
            position: relative;
            animation: statusPulseGlow 2s infinite ease-in-out;
        }

        .addon-card__status-badge--disabled .addon-card__status-pulse {
            background-color: hsl(var(--danger));
            animation: statusPulseGlowDanger 2s infinite ease-in-out;
        }

        /* Pulse Keyframes */
        @keyframes statusPulseGlow {
            0% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 hsla(var(--success) / 0.7);
            }

            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 6px hsla(var(--success) / 0);
            }

            100% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 hsla(var(--success) / 0);
            }
        }

        /* Pulse Keyframes */
        @keyframes statusPulseGlowDanger {
            0% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 hsla(var(--danger) / 0.7);
            }

            70% {
                transform: scale(1.1);
                box-shadow: 0 0 0 6px hsla(var(--danger) / 0);
            }

            100% {
                transform: scale(0.9);
                box-shadow: 0 0 0 0 hsla(var(--danger) / 0);
            }
        }

        /* Status Badge Modifier: addon-card__status-badge--disabled */
        .addon-card__status-badge--disabled {
            background-color: hsla(var(--danger) / 0.08);
            color: hsl(var(--danger));
            border: 1px solid hsla(var(--danger) / 0.15);
            box-shadow: none;
        }

        /* Card Element: middle */
        .addon-card__middle {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .addon-card__name {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 600;
            color: hsl(var(--title-color));
            margin-bottom: 0.6rem;
            letter-spacing: -0.2px;
        }

        .addon-card__description {
            font-size: 0.85rem;
            color: hsl(var(--body-color));
            line-height: 1.55;
            display: -webkit-box;
            margin-bottom: 1.5rem;
        }

        /* Card Element: bottom (Single-row flex) */
        .addon-card__bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px dashed hsl(var(--border-color));
            padding-top: 1.4rem;
        }

        /* Card Elements: meta & price wrap */
        .addon-card__meta-info {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .addon-card__meta-label,
        .addon-card__price-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: hsl(var(--body-color));
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.85;
        }

        .addon-card__meta-value {
            font-family: var(--font-heading);
            font-size: 0.85rem;
            font-weight: 600;
            color: hsl(var(--title-color));
        }

        .addon-card__price-info {
            text-align: right;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .addon-card__price-value {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            color: hsl(var(--primary));
            line-height: 1.1;
        }

        .addon-card__price-small {
            font-size: 0.75rem;
            font-weight: 500;
            color: hsl(var(--body-color));
        }


        /* Large Tablets & Desktop adjust scales */
        @media (max-width: 1200px) {
            .marketplace__title {
                font-size: 1.9rem;
            }
        }

        /* Small Tablets & iPads */
        @media (max-width: 992px) {
            .marketplace__grid {
                grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
                gap: 1.5rem;
            }
        }

        /* Mobile Devices */
        @media (max-width: 600px) {
            .marketplace__main {
                padding: 1.5rem 1.2rem;
            }

            .marketplace__header {
                flex-direction: column;
                gap: 1.5rem;
                align-items: stretch;
            }

            .marketplace__grid {
                grid-template-columns: 1fr;
                gap: 1.2rem;
            }

            .addon-card {
                padding: 1.4rem;
            }

            .marketplace__title {
                font-size: 1.6rem;
            }

            .marketplace__subtitle {
                font-size: 0.88rem;
            }
        }



        .marketplace__empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6rem 2rem;
            background-color: hsla(var(--addon-card-bg) / 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 2px dashed hsl(var(--border-color));
            border-radius: var(--radius-card);
            max-width: 660px;
            margin: 1.5rem auto 0;
            box-shadow: var(--addon-card-shadow);
            transition: transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .marketplace__empty-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, hsla(var(--primary) / 0.08), hsla(var(--primary) / 0.01));
            border: 1px solid hsla(var(--primary) / 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            position: relative;
            box-shadow: 0 8px 24px hsla(var(--primary) / 0.04);
        }

        .marketplace__empty-icon::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border-radius: 50%;
            border: 1px dashed hsla(var(--primary) / 0.25);
            animation: rotateDashedOutline 25s linear infinite;
        }

        @keyframes rotateDashedOutline {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .marketplace__empty-svg {
            width: 60px;
            height: 60px;
        }

        .marketplace__empty-title {
            font-family: var(--font-heading);
            font-size: 1.45rem;
            font-weight: 700;
            color: hsl(var(--title-color));
            margin-bottom: 0.8rem;
            letter-spacing: -0.5px;
        }

        .marketplace__empty-description {
            font-size: 0.88rem;
            color: hsl(var(--body-color));
            max-width: 460px;
            line-height: 1.6;
            margin-bottom: 2.2rem;
        }



        /* Mobile Devices */
        @media (max-width: 600px) {

            .marketplace__empty {
                padding: 4rem 1.5rem;
            }

            .marketplace__empty-title {
                font-size: 1.3rem;
            }
        }

        .addon-card__title {
            font-size: 13px;
            margin-bottom: 1rem;
            border-bottom: 2px dashed hsl(var(--border-color));
            padding-bottom: 2rem;
        }

        .addon-update-notice {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: auto;
            padding: 0.8rem 0.9rem;
            color: hsl(var(--title-color));
            text-align: left;
            background: linear-gradient(105deg, hsl(var(--primary) / 0.11), hsl(var(--primary) / 0.035));
            border: 1px solid hsl(var(--primary) / 0.22);
            border-radius: 13px;
            box-shadow: 0 8px 22px hsl(var(--primary) / 0.07);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .addon-update-notice::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, 0.2) 48%, transparent 65%);
            transform: translateX(-120%);
            animation: addonUpdateShimmer 4.5s ease-in-out infinite;
            pointer-events: none;
        }

        .addon-update-notice:hover,
        .addon-update-notice:focus-visible {
            transform: translateY(-2px);
            border-color: hsl(var(--primary) / 0.5);
            box-shadow: 0 10px 26px hsl(var(--primary) / 0.13);
            outline: none;
        }

        .addon-update-notice__icon {
            width: 35px;
            height: 35px;
            flex: 0 0 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.13);
            border-radius: 10px;
        }

        .addon-update-notice__icon i {
            font-size: 1.15rem;
            animation: addonUpdateArrow 1.8s ease-in-out infinite;
        }

        .addon-update-notice__content {
            min-width: 0;
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .addon-update-notice__content small {
            color: hsl(var(--body-color));
            font-size: 0.68rem;
        }

        .addon-update-notice__content strong {
            color: hsl(var(--primary));
            font-size: 0.9rem;
        }

        .addon-update-notice__action {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-left: auto;
            color: hsl(var(--primary));
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .addon-update-modal__eyebrow {
            display: block;
            margin-bottom: 0.2rem;
            color: hsl(var(--primary));
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .addon-update-form {
            padding: 0.3rem 0.15rem 0.25rem;
        }

        .addon-update-summary {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 1.25rem;
            padding: 1rem;
            background: linear-gradient(120deg, hsl(var(--primary) / 0.09), transparent);
            border: 1px solid hsl(var(--border-color));
            border-radius: 14px;
        }

        .addon-update-summary__icon {
            width: 44px;
            height: 44px;
            flex: 0 0 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.12);
            border-radius: 12px;
            font-size: 1.35rem;
        }

        .addon-update-summary__details {
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .addon-update-summary__details small {
            color: hsl(var(--body-color));
            font-size: 0.7rem;
        }

        .addon-update-summary__details strong {
            overflow: hidden;
            color: hsl(var(--title-color));
            font-size: 0.95rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .addon-update-summary__details span {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: hsl(var(--body-color));
            font-size: 0.72rem;
        }

        .addon-update-summary__details b {
            color: hsl(var(--primary));
        }

        .addon-update-summary__badge {
            margin-left: auto;
            padding: 0.3rem 0.65rem;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.1);
            border: 1px solid hsl(var(--primary) / 0.18);
            border-radius: 50px;
            font-size: 0.64rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .addon-update-dropzone {
            min-height: 205px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            color: hsl(var(--body-color));
            text-align: center;
            background-color: hsl(var(--primary) / 0.025);
            border: 2px dashed hsl(var(--border-color));
            border-radius: 16px;
            cursor: pointer;
            transition: border-color 0.2s ease, background-color 0.2s ease, transform 0.2s ease;
        }

        .addon-update-dropzone:hover,
        .addon-update-dropzone:focus-visible,
        .addon-update-dropzone.is-dragging {
            background-color: hsl(var(--primary) / 0.075);
            border-color: hsl(var(--primary));
            outline: none;
        }

        .addon-update-dropzone.is-dragging {
            transform: scale(1.012);
        }

        .addon-update-dropzone.has-file {
            background-color: hsl(var(--success) / 0.06);
            border-color: hsl(var(--success) / 0.55);
        }

        .addon-update-dropzone input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
        }

        .addon-update-dropzone__graphic {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.1);
            border-radius: 50%;
            font-size: 1.8rem;
        }

        .addon-update-dropzone strong {
            margin-bottom: 0.2rem;
            color: hsl(var(--title-color));
            font-size: 0.95rem;
        }

        .addon-update-dropzone > span:not(.addon-update-dropzone__graphic) {
            font-size: 0.75rem;
        }

        .addon-update-file-name {
            max-width: 100%;
            margin-top: 0.75rem;
            padding: 0.3rem 0.7rem;
            overflow: hidden;
            color: hsl(var(--primary));
            background-color: hsl(var(--primary) / 0.08);
            border-radius: 50px;
            font-size: 0.68rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .addon-update-security-note {
            display: flex;
            align-items: flex-start;
            gap: 0.45rem;
            margin: 1rem 0;
            color: hsl(var(--body-color));
            font-size: 0.72rem;
            line-height: 1.45;
        }

        .addon-update-security-note i {
            flex-shrink: 0;
            margin-top: 0.05rem;
            color: hsl(var(--success));
            font-size: 1rem;
        }

        .addon-update-submit {
            min-height: 46px;
            border-radius: 11px;
        }

        @keyframes addonUpdateShimmer {
            0%, 65% { transform: translateX(-120%); }
            100% { transform: translateX(120%); }
        }

        @keyframes addonUpdateArrow {
            0%, 100% { transform: translateY(2px); }
            50% { transform: translateY(-2px); }
        }

        .addon-installation-progress {
            max-width: 470px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 2rem;
            text-align: center;
        }

        .addon-installation-progress__loader {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 92px;
            height: 92px;
            margin-bottom: 1.75rem;
            color: hsl(var(--primary));
        }

        .addon-installation-progress__loader i {
            font-size: 2rem;
        }

        .addon-installation-progress__ring {
            position: absolute;
            inset: 0;
            border: 2px solid hsl(var(--border-color));
            border-top-color: hsl(var(--primary));
            border-radius: 50%;
            animation: addonInstallerSpin 1.4s linear infinite;
        }

        .addon-installation-progress__ring--inner {
            inset: 9px;
            border-width: 1px;
            border-top-color: transparent;
            border-right-color: hsl(var(--primary) / 0.55);
            animation-direction: reverse;
            animation-duration: 2s;
        }

        .addon-installation-progress__title {
            margin-bottom: 0.65rem;
            color: hsl(var(--title-color));
            font-size: 1.1rem;
            font-weight: 600;
        }

        .addon-installation-progress__description {
            max-width: 410px;
            margin: 0 auto 1.5rem;
            color: hsl(var(--body-color));
            font-size: 0.875rem;
            line-height: 1.65;
        }

        .addon-installation-progress__bar {
            position: relative;
            height: 4px;
            max-width: 340px;
            margin: 0 auto 1.5rem;
            overflow: hidden;
            background-color: hsl(var(--border-color));
            border-radius: 10px;
        }

        .addon-installation-progress__bar span {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -35%;
            width: 35%;
            background-color: hsl(var(--primary));
            border-radius: inherit;
            animation: addonInstallerProgress 1.8s ease-in-out infinite;
        }

        .addon-installation-progress__note {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            margin: 0;
            color: hsl(var(--body-color));
            font-size: 0.78rem;
            opacity: 0.8;
        }

        .addon-installation-progress__note i {
            flex-shrink: 0;
            font-size: 1rem;
        }

        @keyframes addonInstallerSpin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes addonInstallerProgress {
            0% {
                left: -35%;
            }

            55%,
            100% {
                left: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .addon-update-notice::after,
            .addon-update-notice__icon i,
            .addon-installation-progress__ring,
            .addon-installation-progress__bar span {
                animation: none;
            }
        }
    </style>
@endpush
