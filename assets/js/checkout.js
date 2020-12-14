jQuery(function ($) {
    $(document).on('ready', function () {
        if (topdress.islogin) {
            // $(".shipping_address").prepend(topdress.load_button);
            
            $('body').append(topdress.load_form);
            var state_name = $("<input type=\"hidden\" id=\"shipping_state_name\" name=\"shipping_state_name\" />");
            var city_name = $("<input type=\"hidden\" id=\"shipping_city_name\" name=\"shipping_city_name\" />");
            var district_name = $("<input type=\"hidden\" id=\"shipping_district_name\" name=\"shipping_district_name\" />");
            var is_add_new = $("<input type=\"hidden\" id=\"shipping_is_add_new\" name=\"shipping_is_add_new\" />");
            $('.checkout.woocommerce-checkout').append(state_name);
            $('.checkout.woocommerce-checkout').append(city_name);
            $('.checkout.woocommerce-checkout').append(district_name);
            $('.checkout.woocommerce-checkout').append(is_add_new);
            $('#shipping_load_address').on('select2:open', () => {
                $(".select2-results:not(:has(a))").append('<a class="add-new-shipping">Add New Address</a>');
            });

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
        $('#shipping_phone').val($this.attr('address-phone'));
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

    $(document).on('change', '#shipping_load_address', function (e) {
        const id_address = $(this).val();

        $.ajax({
            type: 'POST',
            url: topdress.url,
            data: {
                id_address: id_address,
                action: 'topdress_get_detail_address',
                topdress_get_detail_address_nonce: nonce.get_detail_address
            },
            success: function (response) {
                if (response) {
                    put_field_address(response);
                }
            }
        });

        e.preventDefault();
    });

    $(document).on('click', 'a.add-new-shipping', function () {
        var field = {
            'tag': '',
            'first_name':'',
            'last_name':'',
            'country':'ID',
            'address_1':'',
            'postcode':'',
            'state_id':'',
            'state':'',
            'city':'',
            'city_id':'',
            'district':'',
            'district_id':'',
        }

        put_field_address(field);
        $("input[name='shipping_is_add_new']").val(true);
    });

    function put_field_address(field) { 
        const address_key = '';
        if (field.district_id && field.city_id && field.state_id) {
            address_key = field.district_id + '_' + field.city_id + '_' + field.state_id;
        }

        const address_value = '';
        if (field.district && field.city && field.state) {
            address_value = field.district + ', ' + field.city + ', ' + field.state;
        }

        $('#shipping_tag').val(field.tag);
        $('#shipping_first_name').val(field.first_name);
        $('#shipping_last_name').val(field.last_name);
        $('#shipping_country').val(field.country);
        $('#shipping_address_1').val(field.address_1);
        $('#shipping_postcode').val(field.postcode);
        $('#shipping_phone').val(field.phone);
        $('#shipping_state').val(field.state_id).trigger('change');
        $('#shipping_state_name').val(field.state);
        $('#shipping_city').on('options_loaded', function (e) {
            $('#shipping_city').val(field.city_id).trigger('change');
            $('#shipping_city_name').val(field.city);
        });
        $('#shipping_district').on('options_loaded', function (e) {
            $('#shipping_district').val(field.district_id).trigger('change');
            $('#shipping_district_name').val(field.district);
        });

        if (topdress.is_use_simple_address_field) { 
            $('#shipping_simple_address').append($('<option>', {
                value: address_key
            }).text(address_value));
            $('#select2-shipping_simple_address-container').attr('title', address_value);
            $('#select2-shipping_simple_address-container').html(address_value);
            
            $('#shipping_simple_address').val(address_key).trigger('change');
            $("input[name='shipping_state_name']").val(field.state);
            $("input[name='shipping_city_name']").val(field.city);
            $("input[name='shipping_district_name']").val(field.district);
        }
    }
});