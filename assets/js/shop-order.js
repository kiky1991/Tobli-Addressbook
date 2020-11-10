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
});