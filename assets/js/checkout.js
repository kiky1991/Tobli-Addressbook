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

    $(document).on('change', '#shipping_simple_address', function () {
        const simple_address = $(this).find(":selected").text();
        const addresses = simple_address.split(', ');

        if (addresses[0]) { 
            $("input[name='shipping_district_name']").val(addresses[0]);
        }
        if (addresses[1]) {
            $("input[name='shipping_city_name']").val(addresses[1]);
        }
        if (addresses[2]) {
            $("input[name='shipping_state_name']").val(addresses[2]);
        }
        
    });

    $(document).on('click', 'a#topdress-select-addressbook', function () {
        const $this = $(this);
        
        if (!$this.attr('address-id_address')) { 
            return;
        }

        $('#shipping_tag').val($this.attr('address-tag'));
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

        if (topdress.is_use_simple_address_field) { 
            $('#shipping_simple_address').append($('<option>', {
                value: $this.attr('address-simple_address_id')
            }).text($this.attr('address-simple_address')));
            $('#select2-shipping_simple_address-container').attr('title', $this.attr('address-simple_address'));
            $('#select2-shipping_simple_address-container').html($this.attr('address-simple_address'));
            
            $('#shipping_simple_address').val($this.attr('address-simple_address_id')).trigger('change');
            $("input[name='shipping_state_name']").val($this.attr('address-state'));
            $("input[name='shipping_city_name']").val($this.attr('address-city'));
            $("input[name='shipping_district_name']").val($this.attr('address-district'));
        }

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
                    if (response) {
                        $('.topdress-modal-frame').html(response);
                    }
                }
            });
        }
    });

    $(document).on('click', '.topdress-close-modal', function () {
        $('.topdress-modal.topdress-popup').removeClass('open');
    });

    $(document).on('click', '#button-search-addressbook', function (e) {
        const term = $('input#search_addressbook').val();

        if (term.length == 0 || term.length >= 3) {
            $.ajax({
                type: 'POST',
                url: topdress.url,
                data: {
                    term: term,
                    action: 'topdress_search_address_term',
                    topdress_search_term_nonce: nonce.search_addressbook
                },
                success: function (response) {
                    $('.list-addressbook-body').html(response);
                }
            });
        }

        e.preventDefault();
    });
});