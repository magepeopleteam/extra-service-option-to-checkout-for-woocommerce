(function($) {

    $(document).ready(function() {

        // Service checkbox change
        $(document).on('change', '.meps-service-item-heading input[type="checkbox"]', function() {
            const $this = $(this);
            const parent = $this.parents('.meps-service-item')
            if(this.checked) {
                parent.find('.meps-service-item-content').show();
            } else {
                parent.find('.meps-service-item-content').hide();
            }

            // call ajax for data changing
            $.ajax({
                url: meps_php_vars.ajaxurl,
                type: 'post',
                data: {
                    action: 'meps_change_service_checkbox',
                    isChecked: this.checked,
                    meps_id: $this.data('meps-service-id')
                },
                success: function(data) {
                    meps_ajax_reload();
                    // $this.attr('prop', this.checked)
                }
            })
        })
    })

    function meps_ajax_reload() {
        jQuery('body').trigger('update_checkout'); //  for checkout page
        // For cart page
        const cart_update_btn = jQuery('button[name="update_cart"]');
        cart_update_btn.removeAttr('disabled');
        cart_update_btn.attr('aria-disabled', 'false');
        cart_update_btn.trigger('click');
    }

})(jQuery)