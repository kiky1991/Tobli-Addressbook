jQuery(function ($) {
    $(document).on('ready', function () {
        if (topdress.islogin) {
            $(".shipping_address").prepend(topdress.load_button);
            $('body').append(topdress.load_form);
            var state_name = $("<input type=\"hidden\" id=\"shipping_state_name\" name=\"shipping_state_name\" />");
            var city_name = $("<input type=\"hidden\" id=\"shipping_city_name\" name=\"shipping_city_name\" />");
            var district_name = $("<input type=\"hidden\" id=\"shipping_district_name\" name=\"shipping_district_name\" />");
            $('.checkout.woocommerce-checkout').append(state_name);
            $('.checkout.woocommerce-checkout').append(city_name);
            $('.checkout.woocommerce-checkout').append(district_name);

            $('#shipping_state_name').val($('#select2-shipping_state-container').attr('title'));
            $('#shipping_city').on('options_loaded', function (e) {
                $('#shipping_city_name').val($('#select2-shipping_city-container').attr('title'));
            });
            $('#shipping_district').on('options_loaded', function (e) {
                $('#shipping_district_name').val($('#select2-shipping_district-container').attr('title'));
            });
        }
    });

    $(document).on('change', '#shipping_state', function () {
        $('#shipping_state_name').val($('#select2-shipping_state-container').attr('title'));
    });

    $(document).on('change', '#shipping_city', function () {
        $('#shipping_city_name').val($('#select2-shipping_city-container').attr('title'));
    });

    $(document).on('change', '#shipping_district', function () {
        $('#shipping_district_name').val($('#select2-shipping_district-container').attr('title'));
    });

    $(document).on('click', 'ul.topdress-list-addressbook li', function () {
        const $this = $(this);

        if (!$this.attr('address-id_address')) { 
            return;
        }

        $('#shipping_first_name').val($this.attr('address-first_name'));
        $('#shipping_last_name').val($this.attr('address-last_name'));
        $('#shipping_country').val($this.attr('address-country'));
        $('#shipping_address_1').val($this.attr('address-address_1'));
        $('#shipping_postcode').val($this.attr('address-postcode'));
        $('#shipping_state').val($this.attr('address-state_id')).trigger('change');
        $('#shipping_state_name').val($this.attr('address-state'));
        $('#shipping_city').on('options_loaded', function (e) {
            $('#shipping_city').val($this.attr('address-city_id')).trigger('change');
            $('#shipping_city_name').val($this.attr('address-city'));
        });
        $('#shipping_district').on('options_loaded', function (e) {
            $('#shipping_district').val($this.attr('address-district_id')).trigger('change');
            $('#shipping_district_name').val($this.attr('address-district'));
        });

        $('.topdress-modal.topdress-popup').removeClass('open');
    });

    $(document).on('click', '#topdress_load_addressbook', function () {
        if (topdress.islogin) {
            const search = $("<p class=\"form-row form-row-wide\"><input type=\"text\" id=\"search_addressbook\" name=\"search_addressbook\" placeholder=\"Search Address\" /></p>");
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
                    $('.topdress-modal-frame').prepend(search);
                }
            });
        }
    });

    $(document).on('click', '.topdress-close-modal', function () {
        $('.topdress-modal.topdress-popup').removeClass('open');
    });

    $(document).on('click', 'li.topdress-load-more', function () {
        const page_id = $(this).attr('page-id');
        const term = $('#search_addressbook').val();
        const $this = $(this);
        $this.prop('disabled',true);
        $this.html('Loading..');

        if (topdress.islogin) {
            $.ajax({
                type: 'POST',
                url: topdress.url,
                data: {
                    term: term,
                    page_id: page_id,
                    action: 'topdress_checkout_load_addressbook',
                    topdress_checkout_load_addressbook_nonce: nonce.load_addressbook
                },
                success: function (response) {
                    $('.topdress-modal-frame').append(response);
                    $this.closest('li').remove();
                }
            });
        }
    });

    $(document).on('keyup', '#search_addressbook', function (e) {
        const term = $(this).val();
        const search = $("<p class=\"form-row form-row-wide\"><input type=\"text\" id=\"search_addressbook\" name=\"search_addressbook\" placeholder=\"Search Address\" /></p>");

        if (term.length == 0 || term.length >= 3) {
            $.ajax({
                type: 'POST',
                url: topdress.url,
                data: {
                    term: term,
                    action: 'topdress_checkout_load_addressbook',
                    topdress_checkout_load_addressbook_nonce: nonce.load_addressbook
                },
                success: function (response) {
                    $('.topdress-modal-frame').html(response);
                    $('.topdress-modal-frame').prepend(search);
                }
            });
        }

        e.preventDefault();
    });
});