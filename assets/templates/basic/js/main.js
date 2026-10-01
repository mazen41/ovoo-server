'use strict';
(function ($) {
    // ==========================================
    //      Start Document Ready function
    // ==========================================
    $(document).ready(function () {
        // ============== Header Hide Click On Body Js Start ========
        $('.header-button').on('click', function () {
            $('.body-overlay').toggleClass('show');
        });
        $('.body-overlay').on('click', function () {
            $('.header-button').trigger('click');
            $(this).removeClass('show');
        });
        // =============== Header Hide Click On Body Js End =========

        //============================ Scroll To Top Icon Js Start =========
        (() => {
            const btn = $('.scroll-top');
            $(window).on('scroll', function () {
                if ($(window).scrollTop() >= 30) {
                    $('.header').addClass('fixed-header');
                    btn.addClass('show');
                } else {
                    $('.header').removeClass('fixed-header');
                    btn.removeClass('show');
                }
            });
            btn.on('click', function (e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, '30');
            });
        })()
        // ========================== Header Hide Scroll Bar Js Start =====================
        $('.navbar-toggler.header-button').on('click', function () {
            $('body').toggleClass('scroll-hide-sm');
        });
        $('.body-overlay').on('click', function () {
            $('body').removeClass('scroll-hide-sm');
        });
        // ========================== Header Hide Scroll Bar Js End =====================

        function sidebarControllTwo(barIcon, closeBtn, sidebar) {
            $(`.${barIcon}`).on('click', function () {
                $(`.${sidebar}`).addClass('show-sidebar');
                $('.sidebar-overlay').addClass('show');
            });
            $(`.${closeBtn}, .sidebar-overlay`).on('click', function () {
                $(`.${sidebar}`).removeClass('show-sidebar');
                $('.sidebar-overlay').removeClass('show');
            });
        }

        sidebarControllTwo('dashboard-body__bar-icon', 'sidebar-menu__close', 'sidebar-menu')
        sidebarControllTwo('user-icon', 'close-icon-two', 'body-right')
        sidebarControllTwo('filter-icon', 'close-icon', 'chatbox-area__left')
        sidebarControllTwo('profile-bar-icon', 'sidebar-menu__close', 'profile-page-wrapper')

        // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js Start =====================
        $('body').on('click', '.body-right__top-btn .close-icon-two', function () {
            $('.body-right').removeClass('show-sidebar');
            $('.sidebar-overlay').removeClass('show');
        });
        // ========================== Small Device Header Menu On Click Dropdown menu collapse Stop Js End =====================


        $("body").on("click", ".chatbox-index-inner [data-link]", function (e) {
            const link = $(this).data('link');
            window.location.href = link
        });

        const tooltipSelector = $('body').find('.chatbox-index-inner').length > 0
            ? '[data-bs-toggle-custom="tooltip"]'
            : '[data-bs-toggle="tooltip"]';

        document.querySelectorAll(tooltipSelector).forEach(el => {
            new bootstrap.Tooltip(el, {
                title: el.getAttribute('data-bs-title') ?? el.getAttribute('title') ?? ''
            });
        });

        function reInitToolTip() {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        }



        // Check parent class and hide/show tooltip

        // ========================== Add Attribute For Bg Image Js Start =====================
        $('.bg-img').css('background-image', function () {
            return `url(${$(this).data('background-image')})`;
        });
        // ========================== Add Attribute For Bg Image Js End =====================

        // ========================== add active class to ul>li top Active current page Js Start =====================
        function dynamicActiveMenuClass(selector) {
            if (!($(selector).length)) return;

            let fileName = window.location.pathname.split('/').reverse()[0];
            selector.find('li').each(function () {
                let anchor = $(this).find('a');
                if ($(anchor).attr('href') == fileName) {
                    $(this).addClass('active');
                }
            });
            // if any li has active element add class
            selector.children('li').each(function () {
                if ($(this).find('.active').length) {
                    $(this).addClass('active');
                }
            });
            // if no file name return
            if ('' == fileName) {
                selector.find('li').eq(0).addClass('active');
            }
        }
        dynamicActiveMenuClass($('ul.sidebar-menu-list'));

        // ========================== add active class to ul>li top Active current page Js End =====================
        /*===================== action btn js start here =====================*/
        $('.action-btn__icon').on('click', function () {
            $('.action-dropdown').not($(this).parent().find('.action-dropdown')).removeClass('show');
            $(this).parent().find('.action-dropdown').toggleClass('show');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.action-btn').length) {
                $('.action-dropdown').removeClass('show');
            }
        });
        /*===================== action btn js end here =====================*/
        // ================== Password Show Hide Js Start ==========
        $('.toggle-password').on('click', function () {
            $(this).toggleClass('fa-eye');
            var input = $($(this).attr('id'));
            if (input.attr('type') == 'password') {
                input.attr('type', 'text');
            } else {
                input.attr('type', 'password');
            }
        });
        // =============== Password Show Hide Js End =================

        // ========================= Wow Js Start=====================
        new WOW().init();
        // ========================= Wow Js End=====================

        /*==================== custom dropdown select js ====================*/
        $('.custom--dropdown > .custom--dropdown__selected').on('click', function () {
            $(this).parent().toggleClass('open');
        });
        $('.custom--dropdown > .dropdown-list > .dropdown-list__item').on('click', function () {
            $('.custom--dropdown > .dropdown-list > .dropdown-list__item').removeClass('selected');
            $(this).addClass('selected').parent().parent().removeClass('open').children('.custom--dropdown__selected').html($(this).html());
        });
        $(document).on('keyup', function (evt) {
            if ((evt.keyCode || evt.which) === 27) {
                $('.custom--dropdown').removeClass('open');
            }
        });
        $(document).on('click', function (evt) {
            if ($(evt.target).closest(".custom--dropdown > .custom--dropdown__selected").length === 0) {
                $('.custom--dropdown').removeClass('open');
            }
        });
        /*=============== custom dropdown select js end =================*/




        $('.has-dropdown > a').on("click", function (e) {
            e.preventDefault();

            var $parentLi = $(this).parent(); // The <li> with has-dropdown clicked
            var $submenu = $(this).next('.sidebar-submenu'); // submenu to toggle

            if ($parentLi.hasClass('active')) {
                // Close this submenu and all nested active submenus inside it
                $submenu.slideUp(200);
                $parentLi.removeClass('active');
                // Optional: close all active descendants inside this submenu as well
                $parentLi.find('.has-dropdown.active').removeClass('active').children('.sidebar-submenu').slideUp(200);
            } else {
                // Close only sibling submenus at this level
                $parentLi.siblings('.has-dropdown.active').removeClass('active').children('.sidebar-submenu').slideUp(200);

                // Open this submenu
                $submenu.slideDown(200);
                $parentLi.addClass('active');
            }
        });


        // Sidebar Dropdown Menu End

        // ==================== Dashboard User Profile Dropdown Start ==================
        $('.user-info__button').on('click', function () {
            $('.user-info-dropdown').toggleClass('show');
        });
        $('.user-info__button').attr('tabindex', -1).focus();

        $('.user-info__button').on('focusout', function () {
            $('.user-info-dropdown').removeClass('show');
        });
        // ==================== Dashboard User Profile Dropdown End ==================

        $('.search-area-wrapper .search-btn').on('click', function () {
            $('.search-area-wrapper__inner').addClass('show');
        });
        $('.search-area-wrapper .close-icon').on('click', function () {
            $('.search-area-wrapper__inner').removeClass('show');
        });

        // ================== Sidebar Menu Js End ===============



        // ========================= Select2 Js End ==============
        // select2 with image start here ============================
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<span class="img-flag-inner"><img src="' + $(state.element).attr('data-src') + '" class="img-flag" /> ' + state.text + '<span>'
            );
            return $state;
        };

        if ($('.img-select2').length) {
            $('.img-select2').select2({
                templateResult: formatState,
                templateSelection: formatState
            });
        }


        // Detect Current Language (Laravel)
        let isRTL = $('html').attr('lang') === 'ar';

        // ----- BLOG SLIDER -----
        if ($('.blog-slider').length) {
            $('.blog-slider').slick({
                rtl: isRTL, // 🔥 Dynamic RTL
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true,
                arrows: true,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                dots: true,
                prevArrow:
                    '<button type="button" class="slick-prev"><i class="las la-long-arrow-alt-left"></i></button>',
                nextArrow:
                    '<button type="button" class="slick-next"><i class="las la-long-arrow-alt-right"></i></button>',

                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            dots: false,
                        },
                    },
                ],
            });
        }

        // ----- FEEDBACK SLIDER -----
        if ($('.feedback-slider').length) {
            $('.feedback-slider').slick({
                rtl: isRTL, // 🔥 Dynamic RTL
                slidesToShow: 1,
                slidesToScroll: 1,
                infinite: true,
                arrows: true,
                autoplay: true,
                autoplaySpeed: 2000,
                speed: 1500,
                dots: true,
                prevArrow:
                    '<button type="button" class="slick-prev"><i class="las la-long-arrow-alt-left"></i></button>',
                nextArrow:
                    '<button type="button" class="slick-next"><i class="las la-long-arrow-alt-right"></i></button>',

                responsive: [
                    {
                        breakpoint: 575,
                        settings: {
                            arrows: false,
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });
        }

    });

    // ==========================================
    //      End Document Ready function
    // ==========================================


    // ========================= Preloader Js Start =====================
    $(window).on('load', function () {
        $('.preloader').fadeOut();
    });
    $(window).on('load', function () {
        $("#preloader").fadeOut("slow", function () {
            $("#content").fadeIn("slow");
        });
    });
    // ========================= Preloader Js End=====================

    // ========================= Data Hightlighter =====================
    $('[data-highlight]').each(function () {
        const $this = $(this);
        let originalText = $this.text().trim().split(' ');
        let textLength = originalText.length;
        const highlight = $this.data('highlight').toString();
        const highlight_class = $this.data('highlight-class')?.toString() || 'text--base';
        const highlightToArray = highlight.split(',');
        // Loop through each highlight range
        $.each(highlightToArray, function (i, element) {
            const index = element.toString().split('_');
            var startIndex = index[0];
            var endIndex = index.length > 1 ? index[1] : startIndex;
            if (startIndex < 0) {
                startIndex = textLength - Math.abs(startIndex);
            }
            if (endIndex < 0) {
                endIndex = textLength - Math.abs(endIndex);
            }
            const startIndexValue = originalText[startIndex];
            const endIndexValue = originalText[endIndex];
            if (startIndex === endIndex) {
                originalText[startIndex] = `<span class="${highlight_class}">${startIndexValue}</span>`;
            } else {
                originalText[startIndex] = `<span class="${highlight_class}">${startIndexValue}`;
                originalText[endIndex] = `${endIndexValue}</span>`;
            }
        });
        $this.html(originalText.join(' '))
    });

    $.each($('input, select, textarea'), function (i, element) {
        const $labelElement = $(element).closest('.form-group').find('label').first();
        if (element.hasAttribute('required')) {
            $labelElement.addClass('required');
        }
        $labelElement.addClass('form--label');
    });

    var inputElements = $('input,select,textarea');
    $.each(inputElements, function (index, element) {
        element = $(element);
        element.closest('.form-group').find('label').attr('for', element.attr('name'));
        element.attr('id', element.attr('name'))
    });

    //  Password show hide
    $('.password-show-hide').on('click', function () {
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $(this).parent().find('input').attr('type', 'text');
        } else {
            $(this).parent().find('input').attr('type', 'password');
        }
    });

    // ==================== account switch Dropdown Start ==================
    $('.account-btn').on('click', function () {
        $('.account-dropdown-list').toggleClass('show');
    });
    $('.account-btn').attr('tabindex', -1).focus();

    $('.account-btn').on('focusout', function () {
        $('.account-dropdown-list').removeClass('show');
    });
    // ==================== account switch Dropdown End ==================


    $(document).on('mouseenter', '[data-bs-toggle-custom-two="tooltip"]', function () {
        // Hide all tooltips
        document.querySelectorAll('[data-bs-toggle-custom-two="tooltip"]').forEach(el => {
            let t = bootstrap.Tooltip.getInstance(el);
            if (t) t.hide();
        });

        // Get or create tooltip for current element
        let tooltip = bootstrap.Tooltip.getInstance(this);
        if (!tooltip) {
            tooltip = new bootstrap.Tooltip(this);
        }

        // Show only current
        tooltip.show();
    });


})(jQuery);
