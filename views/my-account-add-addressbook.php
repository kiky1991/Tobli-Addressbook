<div class="woocommerce-notices-wrapper"></div>

<form method="post">

    <h3>Shipping address book</h3>
    <div class="woocommerce-address-fields">

        <div class="woocommerce-address-fields__field-wrapper-pok">
            <?php $form->get_fields('form_new_addressbook'); ?>
            <p class="form-row form-row-first validate-required" id="shipping_first_name_field" data-priority="10"><label for="shipping_first_name" class="">First Name&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="shipping_first_name" id="shipping_first_name" placeholder="" value="Hengky" autocomplete="given-name"></span></p>
            <p class="form-row form-row-last validate-required" id="shipping_last_name_field" data-priority="20"><label for="shipping_last_name" class="">Last Name&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="shipping_last_name" id="shipping_last_name" placeholder="" value="ST" autocomplete="family-name"></span></p>
            <p class="form-row form-row-wide" id="shipping_company_field" data-priority="30"><label for="shipping_company" class="">Company name&nbsp;<span class="optional">(optional)</span></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="shipping_company" id="shipping_company" placeholder="" value="" autocomplete="organization"></span></p>
            <p class="form-row form-row-wide address-field update_totals_on_change validate-required" id="shipping_country_field" data-priority="40"><label for="shipping_country" class="">Country&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><strong>Indonesia</strong><input type="hidden" name="shipping_country" id="shipping_country" value="ID" autocomplete="country" class="country_to_state" readonly="readonly"></span></p>
            <p class="form-row address-field validate-required validate-state form-row-wide" id="shipping_state_field" data-priority="50" data-o_class="form-row form-row-wide address-field validate-required validate-state"><label for="shipping_state" class="">Province&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><select name="shipping_state" id="shipping_state" class="state_select select2-hidden-accessible" autocomplete="address-level1" data-placeholder="Select Province" data-input-classes="" tabindex="-1" aria-hidden="true">
                        <option value="">Select an option…</option>
                        <option value="1">Bali</option>
                        <option value="2">Bangka Belitung</option>
                        <option value="3">Banten</option>
                        <option value="4">Bengkulu</option>
                        <option value="5">DI Yogyakarta</option>
                        <option value="6">DKI Jakarta</option>
                        <option value="7">Gorontalo</option>
                        <option value="8">Jambi</option>
                        <option value="9">Jawa Barat</option>
                        <option value="10">Jawa Tengah</option>
                        <option value="11">Jawa Timur</option>
                        <option value="12">Kalimantan Barat</option>
                        <option value="13">Kalimantan Selatan</option>
                        <option value="14">Kalimantan Tengah</option>
                        <option value="15">Kalimantan Timur</option>
                        <option value="16">Kalimantan Utara</option>
                        <option value="17">Kepulauan Riau</option>
                        <option value="18">Lampung</option>
                        <option value="19">Maluku</option>
                        <option value="20">Maluku Utara</option>
                        <option value="21">Nanggroe Aceh Darussalam (NAD)</option>
                        <option value="22">Nusa Tenggara Barat (NTB)</option>
                        <option value="23">Nusa Tenggara Timur (NTT)</option>
                        <option value="24">Papua</option>
                        <option value="25">Papua Barat</option>
                        <option value="26">Riau</option>
                        <option value="27">Sulawesi Barat</option>
                        <option value="28">Sulawesi Selatan</option>
                        <option value="29">Sulawesi Tengah</option>
                        <option value="30">Sulawesi Tenggara</option>
                        <option value="31">Sulawesi Utara</option>
                        <option value="32">Sumatera Barat</option>
                        <option value="33">Sumatera Selatan</option>
                        <option value="34">Sumatera Utara</option>
                    </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-shipping_state-container" role="combobox"><span class="select2-selection__rendered" id="select2-shipping_state-container" role="textbox" aria-readonly="true" title="Banten">Banten</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></span></p>
            <p class="form-row address-field validate-required init-select2 validate-required form-row-wide" id="shipping_city_field" data-priority="60" data-o_class="form-row form-row-wide address-field validate-required init-select2 validate-required pok_loading"><label for="shipping_city" class="">Town / City&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><select name="shipping_city" id="shipping_city" class="select  select2-hidden-accessible" autocomplete="address-level2" data-placeholder="Select City" tabindex="-1" aria-hidden="true">
                        <option value="">Select City</option>
                        <option value="109">Kota Cilegon</option>
                        <option value="238">Kab. Lebak</option>
                        <option value="344">Kab. Pandeglang</option>
                        <option value="415">Kab. Serang</option>
                        <option value="416">Kota Serang</option>
                        <option value="468">Kab. Tangerang</option>
                        <option value="469">Kota Tangerang</option>
                        <option value="470">Kota Tangerang Selatan</option>
                    </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 183px;"><span class="selection"><span class="select2-selection select2-selection--single" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-shipping_city-container" role="combobox"><span class="select2-selection__rendered" id="select2-shipping_city-container" role="textbox" aria-readonly="true" title="Kab. Lebak">Kab. Lebak</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></span></p>
            <p class="form-row update_totals_on_change address-field init-select2 validate-required" id="shipping_district_field" data-priority="70"><label for="shipping_district" class="">District&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><select name="shipping_district" id="shipping_district" class="select  select2-hidden-accessible update_totals_on_change" data-placeholder="Select District" tabindex="-1" aria-hidden="true">
                        <option value="">Select District</option>
                        <option value="489">Banjarsari</option>
                        <option value="668">Bayah</option>
                        <option value="872">Bojongmanik</option>
                        <option value="1169">Cibadak</option>
                        <option value="1181">Cibeber</option>
                        <option value="1216">Cigemblong</option>
                        <option value="1223">Cihara</option>
                        <option value="1227">Cijaku</option>
                        <option value="1259">Cikulur</option>
                        <option value="1274">Cileles</option>
                        <option value="1282">Cilograng</option>
                        <option value="1296">Cimarga</option>
                        <option value="1311">Cipanas</option>
                        <option value="1333">Cirinten</option>
                        <option value="1376">Curugbitung</option>
                        <option value="1810">Gunung Kencana</option>
                        <option value="2179">Kalanganyar</option>
                        <option value="3057">Lebakgedong</option>
                        <option value="3128">Leuwidamar</option>
                        <option value="3315">Maja</option>
                        <option value="3360">Malingping</option>
                        <option value="3868">Muncang</option>
                        <option value="4322">Panggarangan</option>
                        <option value="4973">Rangkasbitung</option>
                        <option value="5140">Sajira</option>
                        <option value="5746">Sobang</option>
                        <option value="6844">Wanasalam</option>
                        <option value="6883">Warunggunung</option>
                    </select><span class="select2 select2-container select2-container--default" dir="ltr" style="width: 137px;"><span class="selection"><span class="select2-selection select2-selection--single" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-shipping_district-container" role="combobox"><span class="select2-selection__rendered" id="select2-shipping_district-container" role="textbox" aria-readonly="true" title="Bayah">Bayah</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span></span></p>
            <p class="form-row address-field validate-required form-row-wide" id="shipping_address_1_field" data-priority="80"><label for="shipping_address_1" class="">Street address&nbsp;<abbr class="required" title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="shipping_address_1" id="shipping_address_1" placeholder="House number and street name" value="Taman Cibaduyut Indah Blok G" autocomplete="address-line1" data-placeholder="House number and street name"></span></p>
            <p class="form-row address-field form-row-wide" id="shipping_address_2_field" data-priority="90"><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="shipping_address_2" id="shipping_address_2" placeholder="Apartment, suite, unit, etc. (optional)" value="" autocomplete="address-line2" data-placeholder="Apartment, suite, unit, etc. (optional)"></span></p>
            <p class="form-row validate-postcode form-row-wide address-field" id="shipping_postcode_field" data-priority="100" data-o_class="form-row validate-postcode"><label for="shipping_postcode" class="">Postcode / ZIP&nbsp;<span class="optional">(optional)</span></label><span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="shipping_postcode" id="shipping_postcode" placeholder="" value="40239" autocomplete="postal-code"></span></p>
        </div>


        <p>
            <button type="submit" class="button" name="save_address" value="Save address">Save address</button>
            <input type="hidden" id="woocommerce-edit-address-nonce" name="woocommerce-edit-address-nonce" value="ccbb230b18"><input type="hidden" name="_wp_http_referer" value="/my-account/edit-address/shipping/"> <input type="hidden" name="action" value="edit_address">
        </p>
    </div>

</form>

<script type="text/javascript">
    jQuery(function($) {
        $(document).on('ready', function() {
            $('li.woocommerce-MyAccount-navigation-link--edit-address').addClass('is-active');
        });
    });
</script>