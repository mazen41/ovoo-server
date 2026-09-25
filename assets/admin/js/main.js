"use strict";

(function ($) {
    // Password Show Hide Js Start 
    $('.toggle-password').on('click', function () {
        $(this).toggleClass('fa-eye');
        var input = $($(this).attr('id'));
        if (input.attr('type') == 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }
    });

    // Sidebar Dropdown Menu Start
    $('.sidebar-submenu').css('display', 'none');
    $('.dashboard-nav__link.active').next('.sidebar-submenu').slideDown(0);
    $('.has-dropdown > a').on("click",function () {
        $('.sidebar-submenu').slideUp(200);

        if ($(this).hasClass('active')) {
            $('.sidebar-submenu').slideUp(200);
        } else {
            $(this).parent().siblings().children().removeClass('active');
            $(this).next('.sidebar-submenu').slideDown(200);
        }

        $(this).toggleClass('active');
    });

    // Sidebar Icon & Overlay js
    $('.navigation-bar').on('click', function () {
        $('.sidebar-menu').addClass('show-sidebar');
        $('.sidebar-overlay').addClass('show');
        $('body').addClass('scroll-hide ');
    });

    $('.sidebar-menu__close, .sidebar-overlay').on('click', function () {
        $('.sidebar-menu').removeClass('show-sidebar');
        $('.sidebar-overlay').removeClass('show');
        $('.search-card').css('display', 'none')
        $('body').removeClass('scroll-hide');
    });



    // Reposition Input group level
    $(document).find('.input--group').each(function () {
        const width = Math.round($(this).find('.input-group-text').outerWidth())
        if ($(this).find('.form--control').prev().hasClass('input-group-text')) {
            $(this).siblings('.form--label').css('left', `${width + 10}px`)
        }
    });

    //set table data label 
    Array.from(document.querySelectorAll('table')).forEach(table => {
        let heading = table.querySelectorAll('thead tr th');
        Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
            let columArray = Array.from(row.querySelectorAll('td'));
            if (columArray.length <= 1) return;
            columArray.forEach((colum, i) => {
                colum.setAttribute('data-label', heading[i].innerText)
            });
        });
    });

    $.each($('input, select, textarea'), function (i, element) {
        const $labelElement = $(element).closest('.form-group').find('label').first();
        if (element.hasAttribute('required')) {
            $labelElement.addClass('required');
        }
        $labelElement.addClass('form-label');
    });

    //scroll to active menu
    if ($('a').hasClass('active')) {
        $('.dashboard__sidebar-inner').animate({
            scrollTop: $(".active").offset().top - 320
        }, 500);
    }


    //Custom Data Table
    $('.custom-data-table').closest('.card').find('.card-body').attr('style', 'padding-top:0px');
    var tr_elements = $('.custom-data-table tbody tr');
    $(document).on('input', 'input[name=search_table]', function () {

        var search = $(this).val().toUpperCase();
        var match = tr_elements.filter(function (idx, elem) {
            return $(elem).text().trim().toUpperCase().indexOf(search) >= 0 ? elem : null;
        }).sort();
        var table_content = $('.custom-data-table tbody');
        if (match.length == 0) {
            const appConfig = window.app_config;
            table_content.html(`
                <tr class="text-center empty-message-row">
                    <td colspan="100%" class="text-center">
                        <div class="p-5">
                            <img src="${appConfig.empty_image_url}" class="empty-message">
                            <span class="d-block">${appConfig.empty_title}</span>
                            <span class="d-block fs-13 text-muted">${appConfig.empty_message}</span>
                        </div>
                    </td>
                </tr>
                `);
        } else {
            table_content.html(match);
        }
    });


    $('.copyBtn').on('click', function (e) {
        const $this = $(this);
        const text = $this.attr("data-copy");
        const oldHtml = $this.html();

        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = text;
        tempTextArea.style.width = 0;
        tempTextArea.style.height = 0;

        document.body.appendChild(tempTextArea);

        tempTextArea.select();
        tempTextArea.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(text).then(function () {
            $this.html(`<i class="las la-check-double fw-bold me-2"></i> Copied`);
            setTimeout(function () {
                $this.html(oldHtml);
            }, 1500);
        }).catch(function (error) {
            console.error('Copy failed!', error);
        });

        document.body.removeChild(tempTextArea);
    });


    //image uploader 
    $(".image-uploader").on("change", '.image-upload-input', function (e) {
        const $this = $(this);
        const files = e.target.files;
        const file = files[0];
        const supportedFiles = $this.attr('accept') ? $this.attr('accept').split(',') : [];
        const formattedSupportedFiles = supportedFiles.map(supportedFile => `image/${supportedFile.replace('.', '').replace(' ', '')}`);

        if (!formattedSupportedFiles.includes(file.type)) {
            notify('error', 'The uploaded file format is not supported.');
            return;
        }

        const imageUrl = URL.createObjectURL(file);
        const $imagePreview = $(this).closest(".image-uploader").find('.image-upload__thumb img');
        $imagePreview.attr('src', imageUrl);
        $imagePreview.hide();
        $imagePreview.fadeIn();
    });

    $(".image-upload").on("dragover dragenter dragleave", function (e) {
        e.preventDefault();
    });
    $(".image-upload").on("click", function (e) {
        $(this).find('[type="file"]')[0].click();
    });

    $(".image-upload").on("drop", function (e) {
        e.preventDefault();
        const $imageInput = $(this).find('input[type=file]');
        const files = e.originalEvent.dataTransfer.files;

        if (files.length > 1) {
            notify('error', 'Only one file can be uploaded at a time');
            return;
        }
        $imageInput[0].files = e.originalEvent.dataTransfer.files;
        $imageInput.trigger('change');
    });


    // chat media js start here ================



    // chat media js end here ================

    //simple high light search 
    $('.highLightSearchInput').on('input', function (e) {
        const hightLightParentSelector = `.${$(this).data('parent')}`;
        const searchSelector = `.${$(this).data('search')}`;
        const $parent = $(hightLightParentSelector).parent();
        console.log($parent);

        var isResult = false;

        const searchValue = $(this).val().toLowerCase();
        if (searchValue.length <= 0) {
            $(hightLightParentSelector).removeClass('d-none');
            $(searchSelector).each(function () {
                $(this).html($(this).text()); // Reset HTML to original
            });
            return;
        }

        $.each($(hightLightParentSelector), function (i, element) {
            const $sectionNameElement = $(element).find(searchSelector);
            const sectionName = $sectionNameElement.text();
            const sectionNameToLower = sectionName.trim().toLowerCase();
            if (sectionNameToLower.includes(searchValue)) {
                // Create a regex to match the search value, case insensitive
                const regex = new RegExp(`(${searchValue})`, 'gi');
                const highLightText = sectionName.replace(regex,
                    `<span class="highlight-text">$1</span>`);
                $sectionNameElement.html(highLightText);
                $(element).removeClass("d-none");
                isResult = true
            } else {
                $(element).addClass("d-none");
                $sectionNameElement.html(sectionName); // Reset HTML to original
            }
        });

        if (isResult) {
            $parent.find('.highlight-search-empty').remove();
        } else {
            if (!$parent.find('.highlight-search-empty').length) {
                const appConfig = window.app_config;
                $parent.append(`
                    <div class="col-12">
                        <div class="p-5 text-center highlight-search-empty">
                            <img src="${appConfig.empty_image_url}" class="empty-message">
                            <span class="d-block">${appConfig.empty_title}</span>
                            <span class="d-block fs-13 text-muted">${appConfig.empty_message}</span>
                        </div>
                    </div>
                  `);
            }
        }
    });

    //event handler for status switch
    $('.status-switch').on('click', function (e) {
        e.preventDefault();

        const $modal = $('#confirmationModal');
        const action = $(this).data('action');
        const messageEnable = $(this).data('message-enable');
        const messageDisable = $(this).data('message-disable');

        if (e.target.checked) {
            $modal.find(".question").text(messageEnable)
        } else {
            $modal.find(".question").text(messageDisable)
        }
        $modal.find('form').attr('action', action);
        $modal.modal('show');
    });

    //automatic hide tooltip  on small device 
    $('[data-bs-toggle="tooltip"]').on('shown.bs.tooltip', function () {
        if (window.innerWidth <= 768) {
            setTimeout(() => {
                $(this).tooltip('hide');
            }, 1000);
        }
    });
})(jQuery);


//declare some global function 
const getAmount = (amount, precision = null) => {
    const allowPrecision = precision ? precision : window.app_config.allow_precision;
    return parseFloat(amount).toFixed(allowPrecision);
}


