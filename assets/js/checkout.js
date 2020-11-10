jQuery(function ($) {
    $(document).on('ready', function () {
        if (topdress.islogin) {
            $(".shipping_address").prepend(topdress.load_button);
            $('body').append(topdress.load_form);
        }
    });

    $(document).on('click', 'ul.topdress-list-addressbook li', function () {
        const $this = $(this);

        $('#shipping_first_name').val($this.attr('address-first_name'));
        $('#shipping_last_name').val($this.attr('address-last_name'));
        $('#shipping_country').val($this.attr('address-country'));
        $('#shipping_address_1').val($this.attr('address-address_1'));
        $('#shipping_postcode').val($this.attr('address-postcode'));
        $('#shipping_state').val($this.attr('address-state_id')).trigger('change');
        $('#shipping_city').on('options_loaded', function (e) {
            $('#shipping_city').val($this.attr('address-city_id')).trigger('change');
        });
        $('#shipping_district').on('options_loaded', function (e) {
            $('#shipping_district').val($this.attr('address-district_id')).trigger('change');
        });

        $('.topdress-modal.topdress-popup').removeClass('open');
    });

    $(document).on('click', '#topdress_load_addressbook', function () {
        if (topdress.islogin) {
            $('.topdress-modal.topdress-popup').addClass('open');
            $('.topdress-modal-frame').html('<div class="topdress-loading"></div>');

            $.ajax({
                type: 'POST',
                url: topdress.url,
                data: {
                    action: 'topdress_checkout_load_addressbook',
                    topdress_checkout_load_addressbook_nonce: nonce.load_addressbook
                },
                success: function (response) {
                    $('.topdress-modal-frame').html(response);
                }
            });
        }
    });

    $(document).on('click', '.topdress-close-modal', function () {
        $('.topdress-modal.topdress-popup').removeClass('open');
    });
});