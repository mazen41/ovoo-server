@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <x-admin.ui.card>
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Upload Update Package')</h4>
                    <small class="text--secondary">@lang('Upload the update ZIP you downloaded from the marketplace. The files are merged over your installation and only the migrations shipped with that release are applied.')</small>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>

                    <div class="system-update-summary">
                        <div class="system-update-summary__icon"><i class="las la-code-branch"></i></div>
                        <div class="system-update-summary__details">
                            <small>@lang('Installed version')</small>
                            <strong>{{ $systemDetails['name'] }} v{{ $systemDetails['web_version'] }}</strong>
                        </div>
                    </div>

                    <div class="system-update-warning">
                        <i class="las la-exclamation-triangle"></i>
                        <span>@lang('Back up your files and database before continuing. An update cannot be rolled back automatically.')</span>
                    </div>

                    <form action="{{ route('admin.system.update.upload') }}" method="POST"
                        enctype="multipart/form-data" class="system-update-form no-submit-loader">
                        @csrf

                        <div class="system-update-form-content">
                            <label class="system-update-dropzone" for="system-update-file" tabindex="0">
                                <input type="file" name="update_zip" id="system-update-file"
                                    accept=".zip,application/zip" required>
                                <span class="system-update-dropzone__graphic">
                                    <i class="las la-cloud-upload-alt"></i>
                                </span>
                                <strong>@lang('Drop the update ZIP here')</strong>
                                <span>@lang('or click to browse from your computer')</span>
                                <small class="system-update-file-name">@lang('ZIP files only')</small>
                            </label>

                            <div class="system-update-note">
                                <i class="las la-shield-alt"></i>
                                <span>@lang('The product name and version are verified before any file is written.')</span>
                            </div>

                            <button type="submit" class="btn btn--primary w-100 mt-3">
                                <i class="las la-cloud-upload-alt"></i> @lang('Upload & Apply Update')
                            </button>
                        </div>

                        <div class="system-update-progress-content d-none">
                            <div class="system-update-progress" role="status" aria-live="polite">
                                <div class="system-update-progress__loader" aria-hidden="true">
                                    <i class="las la-cog"></i>
                                </div>
                                <h5>@lang('Applying your update')</h5>
                                <p class="text--secondary">
                                    @lang('The package is being uploaded, verified, extracted and migrated.')
                                </p>
                                <div class="system-update-progress__bar" aria-hidden="true"><span></span></div>
                                <p class="system-update-progress__note">
                                    <i class="las la-info-circle"></i>
                                    @lang('Do not close or reload this page until the update is complete.')
                                </p>
                            </div>
                        </div>
                    </form>

                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        (function($) {
            const $form = $('.system-update-form');
            const $input = $('#system-update-file');
            const $dropzone = $('.system-update-dropzone');
            const $formContent = $('.system-update-form-content');
            const $progressContent = $('.system-update-progress-content');
            const defaultFileLabel = $('.system-update-file-name').text();
            let isUpdating = false;

            function setUpdateFile(file) {
                if (!file) {
                    return;
                }

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                $input[0].files = dataTransfer.files;

                $dropzone.addClass('has-file');
                $('.system-update-file-name').text(file.name);
            }

            $dropzone.on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $input.trigger('click');
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

            $input.on('change', function() {
                setUpdateFile(this.files[0]);
            });

            // Leaving mid-update would abort the request between extraction and migration.
            $(window).on('beforeunload', function() {
                if (isUpdating) {
                    return "@lang('The update is still running.')";
                }
            });

            $form.on('submit', function(e) {
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
                        $formContent.addClass('d-none');
                        $progressContent.removeClass('d-none');
                    },
                    success(response) {
                        notify(response.status, response.message);

                        if (response.status == 'success') {
                            updateSucceeded = true;
                            isUpdating = false;
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error(e) {
                        const message = e.responseJSON?.message ||
                            "@lang('The update package could not be applied.')";
                        notify('error', message);
                    },
                    complete() {
                        $submitBtn.prop('disabled', false).html(oldHtml);

                        if (!updateSucceeded) {
                            isUpdating = false;
                            $progressContent.addClass('d-none');
                            $formContent.removeClass('d-none');
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush

@push('style')
    <style>
        .system-update-summary {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border: 1px solid hsl(var(--dark) / 0.08);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .system-update-summary__icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            font-size: 1.35rem;
            background: hsl(var(--primary) / 0.1);
            color: hsl(var(--primary));
        }

        .system-update-summary__details small {
            display: block;
            color: hsl(var(--dark) / 0.6);
        }

        .system-update-warning {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.85rem 1rem;
            border-radius: 8px;
            background: hsl(var(--warning) / 0.1);
            color: hsl(var(--warning));
            margin-bottom: 1.25rem;
        }

        .system-update-dropzone {
            display: block;
            text-align: center;
            padding: 2.5rem 1.25rem;
            border: 2px dashed hsl(var(--dark) / 0.18);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .system-update-dropzone:hover,
        .system-update-dropzone.is-dragging,
        .system-update-dropzone:focus-visible {
            border-color: hsl(var(--primary));
            background: hsl(var(--primary) / 0.04);
        }

        .system-update-dropzone.has-file {
            border-style: solid;
            border-color: hsl(var(--success));
        }

        .system-update-dropzone input[type="file"] {
            display: none;
        }

        .system-update-dropzone__graphic {
            display: block;
            font-size: 2.5rem;
            color: hsl(var(--primary));
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .system-update-dropzone strong {
            display: block;
        }

        .system-update-dropzone span,
        .system-update-dropzone small {
            display: block;
            color: hsl(var(--dark) / 0.6);
        }

        .system-update-note {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            color: hsl(var(--dark) / 0.6);
            font-size: 0.875rem;
        }

        .system-update-progress {
            text-align: center;
            padding: 2rem 1rem;
        }

        .system-update-progress__loader {
            font-size: 2.5rem;
            color: hsl(var(--primary));
            animation: system-update-spin 2s linear infinite;
            display: inline-block;
        }

        @keyframes system-update-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .system-update-progress__bar {
            height: 6px;
            border-radius: 6px;
            overflow: hidden;
            background: hsl(var(--dark) / 0.08);
            margin: 1.25rem auto 0.75rem;
            max-width: 340px;
        }

        .system-update-progress__bar span {
            display: block;
            height: 100%;
            width: 40%;
            border-radius: 6px;
            background: hsl(var(--primary));
            animation: system-update-slide 1.4s ease-in-out infinite;
        }

        @keyframes system-update-slide {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(250%);
            }
        }

        .system-update-progress__note {
            color: hsl(var(--dark) / 0.6);
            font-size: 0.875rem;
            margin-bottom: 0;
        }
    </style>
@endpush
