(function($) {

    // Setting tab
    $('.meps-tab-a').click(function (e) {
        e.preventDefault();
        $('.meps-tab-a').removeClass('active-a')
        $(this).addClass('active-a')

        $(".meps-tab").removeClass('meps-tab-active');
        $(".meps-tab[data-id='" + $(this).attr('data-id') + "']").addClass("meps-tab-active");
        $(this).parent().find(".tab-a").addClass('active-a');
    });

    // Form field group button action
    $('.meps-field-group-btn').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        const status = $this.data('status');
        const field_header = $this.parents('.meps-field-header');
        const parent = $this.parents('.meps-form-builder-row');
        if(status === 'close') {
            parent.find('.meps-form-builder-inner-container').slideDown();
            $this.data('status', 'open')
            // $this.text('Shrink')
        } else {
            parent.find('.meps-form-builder-inner-container').slideUp();
            $this.data('status', 'close')
            // $this.text('Expand')
        }
    })

    // Add new service row
    $('.meps-add-new-btn').click(function(e) {
        e.preventDefault();
        const target = $('.meps-form-builder');
        const newService = $('.meps-form-field-blueprint').clone(true);
        newService.removeClass('meps-form-field-blueprint')
        newService.insertBefore(target.find('tbody>tr:last'));
    })

    // remove service row
    $('.mep-service-remove').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        $this.parents('tr').remove();
    })
    
})(jQuery);