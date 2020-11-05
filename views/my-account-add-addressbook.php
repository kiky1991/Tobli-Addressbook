<div class="woocommerce-notices-wrapper"></div>

<form method="post">

    <h3><?php esc_html_e('Shipping address book', 'topdress'); ?></h3>
    <div class="woocommerce-address-fields">
        <div class="woocommerce-address-fields__field-wrapper-pok">
            <?php $form->get_fields('form_new_addressbook'); ?>
            <input type="hidden" name="shipping_state_name" id="shipping_state_name">
            <input type="hidden" name="shipping_city_name" id="shipping_city_name">
            <input type="hidden" name="shipping_district_name" id="shipping_district_name">
        </div>
        <p class="submit">
            <?php wp_nonce_field('topdress_save_address', 'topdress_save_address_nonce'); ?>
            <input type="submit" name="topdress_submit_address" class="woocommerce-button button" value="<?php esc_attr_e('Save', 'topdress'); ?>">
            <input type="hidden" name="action" value="topdress_save_address" />
        </p>
    </div>
</form>

<script type="text/javascript">
    jQuery(function($) {
        $(document).on('ready', function() {
            $('li.woocommerce-MyAccount-navigation-link--edit-address').addClass('is-active');
            const state = $('#select2-shipping_state-container').attr('title');
            const city = $('#select2-shipping_city-container').attr('title');
            const district = $('#select2-shipping_district-container').attr('title');
            $('#shipping_state_name').val(state);
            $('#shipping_city_name').val(city);
            $('#shipping_district_name').val(district);
        });

        $('#shipping_state').on('change', function() {
            const state = $('#select2-shipping_state-container').attr('title');
            $('#shipping_state_name').val(state);
        });

        $('#shipping_city').on('change', function() {
            const city = $('#select2-shipping_city-container').attr('title');
            $('#shipping_city_name').val(city);
        });

        $('#shipping_district').on('change', function() {
            const district = $('#select2-shipping_district-container').attr('title');
            $('#shipping_district_name').val(district);
        });
    });
</script>