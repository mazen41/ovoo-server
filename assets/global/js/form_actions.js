(function ($) {
    "use strict"

    $('[name=form_type]').on('change', function () {
        var formType = $(this).val();
        var extraFields = formGenerator.extraFields(formType);
        $('.extra_area').html(extraFields);
        $('.extra_area').find('select').select2({
            dropdownParent: $('.form-generator-filed-area')
        });
    }).change();


    $(document).on('click', '.addOption', function () {
        var html = formGenerator.addOptions();
        $('.options').append(html);
    });

    $(document).on('click', '.removeOption', function () {
        $(this).closest('.form-group').remove();
    });

    $(document).on('click', '.editFormData', function () {
        formGenerator.formEdit($(this));
        
        $('.extra_area').find('select').select2({
            dropdownParent: $('.form-generator-filed-area')
        });
        
        if(window.innerWidth <= 991 ){
            $('html,body').animate({ scrollTop: 9999 }, 'slow');
        }
    });

    $(document).on('click', '.removeFormData', function () {
        const removeId=Number($(this).parent().find(".editFormData").data('update_id'));
        $(this).closest('.form-field-wrapper').remove();
        $('.submitRequired').removeClass('d-none');
        
        if (!$('body').find(".form-field-wrapper").length) {
            $("body .empty-message-wrapper").removeClass('d-none');
            $('.submitRequired').addClass('d-none');
        }else{
            $('.submitRequired').removeClass('d-none');
        }
        const updateId=Number($(".form-edit__wrapper").find("input[name=update_id]").val());
        
        if(removeId ==  updateId){
            formGenerator.resetAll();
        }
        
    });

    var updateId = formGenerator.totalField;
    $(formGenerator.formClassName).on('submit', function (e) {
        updateId += 1;
        e.preventDefault();
        var form = $("body").find(".form-generator-filed-area");
        var formItem = formGenerator.formsToJson(form);
        formGenerator.makeFormHtml(formItem, updateId);
        $('.submitRequired').removeClass('d-none');
        $("body .generate-form-submit-btn-wrapper").removeClass('d-none');
        $("body .empty-message-wrapper").addClass('d-none');
        formGenerator.resetAll();
    });
})(jQuery)

