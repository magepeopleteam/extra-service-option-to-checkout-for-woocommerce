(function($) {

    $(document).ready(function() {
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

    // Make all check/uncheck
    // $('.meps-table .meps-check input[type="checkbox"]').change(function() {
    //     const $this = $(this);
    //     const parent = $this.parents('.meps-table')
    //     if(this.checked) {
    //         parent.find('tbody tr').each(function() {
    //             $(this).find('input[type="checkbox"]').prop('checked', true);
    //         });
    //     } else {
    //         parent.find('tbody tr').each(function() {
    //             $(this).find('input[type="checkbox"]').prop('checked', false);
    //         });
    //     }
    // });

    // Change field type
    $('.meps-service-field-type').change(function() {
        const value = $(this).val();
        if(value === 'select' || value === 'radio') {
            $(this).parents('.meps-form-builder-2nd-level-inner-container').find('.meps-field-value-container').addClass('meps-show')
        } else {
            $(this).parents('.meps-form-builder-2nd-level-inner-container').find('.meps-field-value-container').removeClass('meps-show')
        }
    })

    // Add new service row
    $('.meps-add-new-btn').click(function(e) {
        e.preventDefault();
        const table = $('.meps-form-builder');
        const newService = $('.meps-form-field-blueprint').clone(true);
        const trCount = table.find('tbody tr').length;
        console.log(trCount);
        newService.find('.meps-service-active-checkbox').attr('name', 'meps[service]['+(trCount - 1)+'][active]');
        newService.find('.meps-service-title').attr('name', 'meps[service]['+(trCount - 1)+'][title]');
        newService.find('.meps-service-price').attr('name', 'meps[service]['+(trCount - 1)+'][price]');
        newService.removeClass('meps-form-field-blueprint');
        newService.attr('data-index', trCount - 1);
        newService.insertBefore(table.find('tbody>tr:last'));
    })

    // Add second level
    $('.meps-add-field-btn').click(function(e) {
        e.preventDefault();
        const serviceEl = $(this).parents('.meps-form-builder-row');
        const serviceIndex = serviceEl.attr('data-index');
        const parent = $(this).parents('.meps-form-builder-2nd-level-container');
        const blueprintEl = parent.find('.meps-2nd-level-blueprint').clone(true);
        const itemCount = parent.find('.meps-form-builder-2nd-level-sub-container .meps-form-builder-2nd-level-inner-container').length;
        console.log(serviceIndex, itemCount);
        blueprintEl.removeClass('meps-2nd-level-blueprint');
        blueprintEl.find('.meps-service-label').attr('name', 'meps[service]['+(serviceIndex)+'][item]['+(itemCount)+'][label]');
        blueprintEl.find('.meps-service-placeholder').attr('name', 'meps[service]['+(serviceIndex)+'][item]['+(itemCount)+'][placeholder]');
        blueprintEl.find('.meps-service-field-type').attr('name', 'meps[service]['+(serviceIndex)+'][item]['+(itemCount)+'][field_type]');
        blueprintEl.find('.meps-service-required').attr('name', 'meps[service]['+(serviceIndex)+'][item]['+(itemCount)+'][required]');
        blueprintEl.find('.meps-service-field-values').attr('name', 'meps[service]['+(serviceIndex)+'][item]['+(itemCount)+'][field_values]');

        blueprintEl.find('.meps-service-condition-product').attr('name', 'meps[service]['+(serviceIndex)+'][condition]['+(itemCount)+'][products][]');
        blueprintEl.find('.meps-service-condition-type').attr('name', 'meps[service]['+(serviceIndex)+'][condition]['+(itemCount)+'][type]');
        blueprintEl.find('.meps-service-condition-value').attr('name', 'meps[service]['+(serviceIndex)+'][condition]['+(itemCount)+'][value]');
        blueprintEl.find('.meps-service-condition-operator').attr('name', 'meps[service]['+(serviceIndex)+'][condition]['+(itemCount)+'][operator]');
        parent.find('.meps-form-builder-2nd-level-sub-container').append(blueprintEl);
        
    })

    // remove service row
    $('.mep-service-remove').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        $this.parents('tr').remove();
    })

    // remove 2nd level row
    $('.meps-remove-2nd-level').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        $this.parents('.meps-form-builder-2nd-level-inner-container').remove();
    })

    // section extend/collapse
    $('.meps-section-btn').click(function(e) {
        e.preventDefault();
        const $this = $(this);
        const parent = $this.parents('.meps-section');
        parent.find('.meps-section-content').slideToggle('fast');
    })
    })
    
})(jQuery);