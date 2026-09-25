@extends('admin.layouts.app')
@section('panel')
    @if ($pData->is_default == Status::NO)
        <div class="row mb-4">
            <div class="col-md-12">
                <x-admin.ui.card>
                    <x-admin.ui.card.body>
                        <form action="{{ route('admin.frontend.manage.pages.update') }}" method="POST">
                            @csrf
                            <input name="id" type="hidden" value="{{ $pData->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label> @lang('Page Name')</label>
                                            <a href="javascript:void(0)" class="buildSlug"><i class="las la-link"></i>
                                                @lang('Make Slug')</a>
                                        </div>
                                        <input class="form-control" name="name" type="text"
                                            value="{{ $pData->name }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <label> @lang('Page Slug')</label>
                                            <div class="slug-verification d-none"></div>
                                        </div>
                                        <input class="form-control" name="slug" type="text"
                                            value="{{ $pData->slug }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <x-admin.ui.btn.submit class="w-100" />
                                    </div>
                                </div>
                            </div>
                        </form>
                    </x-admin.ui.card.body>
                </x-admin.ui.card>
            </div>
        </div>
    @endif

    <div class="row gy-3">
        <div class="col-lg-7">
            <x-admin.ui.card class="h-100">
                <x-admin.ui.card.header>
                    <h4 class="card-title">{{ __($pData->name) }} @lang('Page')</h4>
                    <small class="text--primary fw-500">@lang('Below are the sections already added to this page')</small>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <div class="submitRequired bg--warning d-none form-change-alert"><i
                            class="fas fa-exclamation-triangle"></i>
                        @lang('You\'ve to click on the Update Now button to apply the changes')</div>
                    <form action="{{ route('admin.frontend.manage.section.update', $pData->id) }}" method="post">
                        @csrf
                        <ol id="page_sections" class="simple_with_drop vertical sec-item ps-0">
                            @if ($pData->secs != null)
                                @foreach (json_decode($pData->secs) as $sec)
                                    <li class="sortable-item highlight icon-move item">
                                        <i class="sortable-icon me-2"></i>
                                        <span class="d-inline-block me-auto">
                                            {{ __(@$sections[$sec]['name']) }} <br>
                                            <small
                                                class="fs-12 d-none d-md-block">{{ __(@$sections[$sec]['description']) }}</small>
                                        </span>
                                        <button class="btn btn--danger remove-icon remove-icon-color">
                                            <i class=" la la-trash"></i>
                                        </button>
                                        <input name="secs[]" type="hidden" value="{{ $sec }}">
                                    </li>
                                @endforeach
                            @else
                                <li class="empty-state d-flex flex-column">
                                    <div class="drag-drop-image">
                                        <img src="{{ asset('assets/images/drag-and-drop.png') }}">
                                    </div>
                                    <span>@lang('Drag & drop your section here')</span>
                                </li>
                            @endif
                        </ol>
                        <div class=" mt-3">
                            <x-admin.ui.btn.submit text="Update" />
                        </div>
                    </form>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>

        <div class="col-lg-5">
            <x-admin.ui.card class="section-list-card h-100">
                <x-admin.ui.card.header>
                    <h4 class="card-title">@lang('Sections')</h4>
                    <small class="text--primary fw-500">@lang('Drag a section to the left to display it on the page.')</small>
                </x-admin.ui.card.header>
                <x-admin.ui.card.body>
                    <ol id="sections_items" class="simple_with_no_drop vertical ps-0">
                        @foreach ($sections as $k => $secs)
                            @if (!@$secs['no_selection'])
                                <li class="highlight icon-move clearfix" data-key="{{ $k }}">
                                    <i class="sortable-icon me-2"></i>
                                    <span class="d-inline-block me-auto">
                                        {{ __($secs['name']) }} <br />
                                        <small
                                            class="fs-12 d-none d-md-block">{{ strLimit(__($secs['description']), 70) }}</small>
                                    </span>
                                    <button class="btn btn--danger remove-icon remove-icon-color">
                                        <i class=" la la-trash"></i>
                                    </button>
                                    @if ($secs['builder'])
                                        <div class="float-end d-inline-block manage-content">
                                            <a class="btn btn--primary text-center"
                                                href="{{ route('admin.frontend.sections', $k) }}" target="_blank">
                                                <i class="la la-cog m-0 p-0"></i>
                                            </a>
                                        </div>
                                    @endif
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </x-admin.ui.card.body>
            </x-admin.ui.card>
        </div>
    </div>
@stop
@push('script-lib')
    <script src="{{ asset('assets/admin/js/jquery-ui.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var initialSections = getSectionKeys();

            function getSectionKeys() {
                return $(document).find('#page_sections input[name="secs[]"]').map(function() {
                    return $(this).val();
                }).get();
            }

            $("#page_sections").sortable({
                items: "li:not(.empty-state)",
                start: function(event, ui) {
                    // Store original position
                    ui.placeholder.height(ui.item.height());
                },
                containment: $("#page_sections"),
                update: () => handleShowSubmissionAlert()
            });

            $("#sections_items li").draggable({
                stop: function(event, ui) {
                    const element = ui.helper;
                    const key = element.data('key');
                    element.append(`<input type="hidden" name="secs[]" value="${key}">`)

                    if ($('#page_sections').children().length == 0) {
                        watchState(true);
                    }
                    handleShowSubmissionAlert();
                    $('#page_sections').removeClass('dropping');
                },
                start: function(event, ui, offset) {
                    const height = $('.empty-state').outerHeight();

                    if ($('#page_sections').children().length == 1) {
                        $('.empty-state').remove();
                    }

                    $('#page_sections').addClass('dropping').css('min-height', `${height}px`);
                },
                helper: function() {
                    var originalElement = $(this);
                    var originalWidth = '100%';
                    var clonedElement = originalElement.clone();
                    clonedElement.css('width', originalWidth);
                    const len = $('#page_sections').children().length;
                    return clonedElement;
                },
                connectToSortable: '#page_sections',
                containment: $(".dashboard__area-inner"),
            });

            $("#page_sections").droppable({
                accept: '#sections_items li',
                drop: function(event, ui) {
                    let originalWidth = $(event.target).width();
                    $(this).append(ui.draggable);
                    ui.draggable.removeAttr('style');
                    ui.draggable.removeClass();
                    ui.draggable.addClass('highlight icon-move item ui-sortable-handle').css('height',
                        'auto');
                }
            });

            $(document).on('click', ".remove-icon", function() {
                $(this).parent('.highlight').remove();
                handleShowSubmissionAlert();
                watchState();
            });

            function watchState(override = false) {
                if ($('#page_sections').children().length == 0 || override) {
                    $('#page_sections').html(`<li class="empty-state d-flex flex-column">
                        <div class="drag-drop-image">
                            <img src="{{ asset('assets/images/drag-and-drop.png') }}">
                        </div>
                        <span>@lang('Drag & drop your section here')</span>
                    </li>`);
                }
            }

            function handleShowSubmissionAlert() {
                const arraysAreEqual = (arr1, arr2) => JSON.stringify(arr1) === JSON.stringify(arr2);

                if (!arraysAreEqual(initialSections, getSectionKeys())) {
                    $('.submitRequired').removeClass('d-none');
                }
            }



            $('.buildSlug').on('click', function() {
                let closestForm = $(this).closest('form');
                let title = closestForm.find('[name=name]').val();
                closestForm.find('[name=slug]').val(title);
                closestForm.find('[name=slug]').trigger('input');
            });

            $('[name=slug]').on('input', function() {
                let closestForm = $(this).closest('form');
                closestForm.find('[type=submit]').addClass('disabled')
                let slug = $(this).val();
                slug = slug.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
                $(this).val(slug)
                if (slug) {
                    $('.slug-verification').removeClass('d-none');
                    $('.slug-verification').html(`
                        <small class="text--info"><i class="las la-spinner la-spin"></i> @lang('Checking')</small>
                    `);
                    $.get("{{ route('admin.frontend.manage.pages.check.slug', $pData->id) }}", {
                        slug: slug
                    }, function(response) {
                        if (!response.exists) {
                            $('.slug-verification').html(`
                                <small class="text--success"><i class="las la-check"></i> @lang('Available')</small>
                            `);
                            closestForm.find('[type=submit]').removeClass('disabled')
                        }
                        if (response.exists) {
                            $('.slug-verification').html(`
                                <small class="text--danger"><i class="las la-times"></i> @lang('Slug already exists')</small>
                            `);
                        }
                    });
                } else {
                    $('.slug-verification').addClass('d-none');
                }
            })
        })(jQuery);
    </script>
@endpush

@push('breadcrumb-plugins')
    <x-back_btn route="{{ route('admin.frontend.manage.pages') }}" />
@endpush

@push('style')
    <style>
        .simple_with_drop,
        .simple_with_no_drop {
            user-select: none;
        }

        .icon-move .sortable-icon {
            font-family: "Line Awesome Free";
            font-weight: 900;
            font-style: normal;
            font-size: 14px;
        }

        .simple_with_no_drop .sortable-icon:before {
            content: "\f060";
        }

        .simple_with_drop .sortable-icon:before {
            content: "\f2a1";
        }

        .highlight {
            background: #000;
            color: #464646;
        }

        .vertical {
            margin: 0 0 9px 0;
            min-height: 10px;
        }

        .icon-move {
            background-position: -168px -72px;
        }

        .icon-move {
            cursor: pointer;
        }

        .vertical li {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
            padding: 10px;
            color: #474747;
            background: hsl(var(--white));
            font-size: 16px;
            font-weight: 600;
            border-radius: .5rem;
            cursor: move;
            position: relative;
            min-height: 55px;
            border: 1px solid rgb(0 0 0 / 6%);
        }

        .sec-item li {
            margin: 10px 0;
            padding: 10px;
            color: #424242 !important;
            background: hsl(var(--white));
            font-size: 24px;
            font-weight: 600;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            border-radius: .5rem;
            border: 1px solid rgb(0 0 0 / 6%);
        }
            [data-theme=dark] .sec-item li, [data-theme=dark]  .vertical li {
            background: hsl(var(--bg-color));
            color: hsl(var(--secondary)) !important;
        }


        */ .ol.sec-item li.d-none {
            display: none !important;
        }

        .dragged {
            display: none !important;
        }

        .vertical li i.remove-icon {
            display: none !important;
        }

        .sec-item li i.remove-icon {
            display: block !important;
            cursor: pointer;
        }

        .sec-item li .manage-content {
            display: none !important;
        }

        .vertical li span {
            font-size: 0.938rem;
        }



        .bodywrapper__inner {
            overflow: hidden;
        }

        @media(max-width: 767px) {
            .vertical li span {
                font-size: 12px !important;
            }

            .manage-content,
            .simple_with_drop .remove-icon {
                position: absolute;
                top: 50%;
                right: 5px;
                transform: translateY(-50%);
            }
        }

        @media(max-width: 480px) {

            .sec-item li,
            .vertical li {
                padding-right: 30px;
                flex-wrap: nowrap;
            }

            .vertical li span {
                font-size: 10px !important;
                display: block !important;
                line-height: 12px;
            }



            .simple_with_drop i,
            .simple_with_no_drop i {
                font-size: 15px !important;
            }
        }

        .empty-state {
            border: 2px dotted #ccc !important;
            text-align: center !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 3rem !important;
            cursor: default !important;
        }

        #page_sections.dropping {
            border: 2px solid #4634FF;
            border-radius: 10px;
            border: 2px dotted #ccc !important;
            padding: 0 1rem !important;
        }


        .drag-drop-image {
            width: 100px;
        }

        .section-list-card .remove-icon {
            display: none;
        }
    </style>
@endpush
