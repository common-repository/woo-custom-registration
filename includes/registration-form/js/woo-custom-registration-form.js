(function ($) {
    'use strict';

    $(document).ready(function () {

        var post_id = $('#woo_custom_form_current_post_id').val();

        onElementChange($('#woo_custom_form_element_type').val(), post_id);

        $('#woo_custom_form_element_type').on('change', function () {
            onElementChange($(this).val(), post_id);
        });
    });

    function onElementChange(element, post_id) {

        loader(true);

        $.ajax({
            type: 'POST',
            url: woo_custom_registration.ajaxurl,
            data: {
                action: 'fetch_element_settings',
                security: woo_custom_registration.fetch_element_nonce,
                element: element,
                post_id: post_id
            },
            dataType: "json",
            success: function (response) {
                $('#woocommerce-custom-registration-form-wrapper').html(response.settings);
                $('#woo_custom_registration_element_options').tagsInput({
                    'defaultText': woo_custom_registration.select_add_option_txt,
                    'delimiter': '|'
                });
                loader(false);
            }
        }).fail(function (response) {
            if (window.console && window.console.log) {
                console.log(response);
            }
        });
    }

    function loader(show = false) {
        if (show) {
            $('.woocommerce-custom-registration-form-loader').show();
            $('#woo_custom_form_element_type').css('margin-top', '-32px');
            $('#woocommerce-custom-registration-form-wrapper').hide();
            $('#woocommerce-custom-registration-form-common-wrapper').hide();
        } else {
            $('.woocommerce-custom-registration-form-loader').hide();
            $('#woo_custom_form_element_type').css('margin-top', '1px');
            $('#woocommerce-custom-registration-form-wrapper').show();
            $('#woocommerce-custom-registration-form-common-wrapper').show();
        }
    }

})(jQuery);
