(function ($) {
    "use strict";

    //manipulate name to id,label 
    var inputElements = $('[type=text],select,textarea');
    $.each(inputElements, function (index, element) {
        element = $(element);
        element.closest('.form-group').find('label').attr('for', element.attr('name'));
        element.attr('id', element.attr('name'))
    });

    //add required class
    $.each($('input, select, textarea'), function (i, element) {
        var elementType = $(element);
        if (elementType.attr('type') != 'checkbox') {
            if (element.hasAttribute('required')) {
                $(element).closest('.form-group').find('label').addClass('required');
            }
        }
    });

    //set data-label attribute to table column    
    Array.from(document.querySelectorAll('table')).forEach(table => {
        let heading = table.querySelectorAll('thead tr th');
        Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
            if (row.querySelectorAll('td').length <= 1) return;
            Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
                colum.setAttribute('data-label', heading[i].innerText)
            });
        });
    });

    //form submit loader
    $("form:not(.no-submit-loader)").on('submit', function (e) {
        let $this = $(this);
        let $submitBtn = $this.find(`button[type=submit]`).not(".generatorSubmit");
        let oldHtml = $submitBtn.html();

        $submitBtn.addClass('disabled').attr("disabled", true).html(`
            <div class="button-loader d-flex gap-2 flex-wrap align-items-center justify-content-center">
                <div class="spinner-border"></div><span>Loading...</span>
            </div>
        `);

        //for gcaptcha 
        if ($this.hasClass('verify-gcaptcha')) {
            setTimeout(function () {
                if ($this.find('#g-recaptcha-error span').length) {
                    $submitBtn.removeClass('disabled').attr("disabled", false);
                    $submitBtn.html(oldHtml);
                }
            }, 500);
        }
    });
    //active tooltip
    const tooltipTriggerList = document.querySelectorAll('[title]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    //initialize select2
    $.each($('.select2'), function () {
        $(this)
            .wrap(`<div class="position-relative"></div>`)
            .select2({
                dropdownParent: $(this).parent(),
            });
    });

    $.each($('.select2-auto-tokenize'), function () {
        $(this)
            .wrap(`<div class="position-relative"></div>`)
            .select2({
                tags: true,
                tokenSeparators: [','],
                dropdownParent: $(this).parent()
            });
    });

    //data image to css url
    $('.bg-img').css('background', function () {
        var bg = 'url(' + $(this).data('background-image') + ')';
        return bg;
    });

    //summer not initialize 
    if ($(".editor").length > 0) {
        $('.editor').summernote({
            height: 200
        });
    }
})(jQuery);