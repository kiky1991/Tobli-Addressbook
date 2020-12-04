<?php

/**
 * Form class
 */
class TOPDRESS_Form
{
    /**
     * TOPDRESS_Form::__construct
     * 
     * Main construct
     */
    public function __construct()
    {
        $this->core = new TOPDRESS_Core();
    }

    /**
     * TOPDRESS_Form::form_dropship_information
     * 
     * Form for dropship information my-account page
     * 
     * @return  array
     */
    public function form_new_addressbook()
    {
        global $woocommerce;
        $countries_obj   = new WC_Countries();
        $countries   = $countries_obj->__get('countries');
        $default_country = $countries_obj->get_base_country();
        $default_county_states = $countries_obj->get_states($default_country);

        $errors = get_user_meta(get_current_user_id(), 'error_field_addressbook', true);
        if (!empty($errors)) {
            delete_user_meta(get_current_user_id(), 'error_field_addressbook');
        }

        return apply_filters('form_new_addressbook', array(
            'tag' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Tag', 'topdress'),
                    'placeholder' => __('E.g: Home, Workplace, Customer, etc.', 'topdress'),
                    'required'    => false,
                    'class'       => array('form-row-wide')
                ),
                'value' => isset($errors['tag']) ? $errors['tag'] : ''
            ),
            'first_name' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('First Name', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-first'),
                    'autocomplete' => 'given-name'
                ),
                'value' => isset($errors['first_name']) ? $errors['first_name'] : ''
            ),
            'last_name' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Last Name', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-last'),
                    'autocomplete' => 'family-name'
                ),
                'value' => isset($errors['last_name']) ? $errors['last_name'] : ''
            ),
            'country' => array(
                'field' => array(
                    'type'        => 'country',
                    'label'       => __('Country', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-wide', 'address-field', 'update_totals_on_change'),
                    'autocomplete' => 'country'
                ),
                'value' => isset($errors['country']) ? $errors['country'] : ''
            ),
            'shipping_state' => array(
                'field' => array(
                    'type'        => 'state',
                    'label'       => __('Province', 'topdress'),
                    'placeholder' => __('Select Province', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-wide', 'address-field', 'validate-required', 'init-select2'),
                    'validate'    => array('state'),
                    'autocomplete' => 'address-level1',
                    'country_field' => 'shipping_country',
                    'country'   => 'ID',
                    'options' => $default_county_states
                ),
                'value' => 34
            ),
            'shipping_city' => array(
                'field' => array(
                    'type'        => 'select',
                    'label'       => __('City', 'topdress'),
                    'placeholder' => __('Select City', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-wide', 'address-field', 'validate-required', 'init-select2'),
                    'autocomplete' => 'address-level2',
                    'options'        => array('' => __('Select City', 'pok')),
                ),
                'value' => isset($errors['shipping_city']) ? $errors['shipping_city'] : ''
            ),
            'shipping_district' => array(
                'field' => array(
                    'type'        => 'select',
                    'label'       => __('District', 'topdress'),
                    'placeholder' => __('Select District', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-wide', 'update_totals_on_change', 'address-field', 'init-select2'),
                    'options'   => array('' => __('Select District', 'pok')),
                ),
                'value' => isset($errors['shipping_district']) ? $errors['shipping_district'] : ''
            ),
            'address_1' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Address', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-wide')
                ),
                'value' => isset($errors['address_1']) ? $errors['address_1'] : ''
            ),
            'phone' => array(
                'field' => array(
                    'type'        => 'tel',
                    'label'       => __('Phone', 'topdrop'),
                    'placeholder' => __('', 'topdrop'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-last')
                ),
                'value' => isset($errors['phone']) ? $errors['phone'] : ''
            ),
            'postcode' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Postcode', 'topdrop'),
                    'placeholder' => __('', 'topdrop'),
                    'required'    => false,
                    'class'       => array('form-row', 'form-row-last'),
                    'validate'  => array('postcode')
                ),
                'value' => isset($errors['postcode']) ? $errors['postcode'] : ''
            ),
        ));
    }

    public function form_edit_addressbook()
    {
        $id = sanitize_text_field(wp_slash($_GET['id']));
        $q = array(
            'id_address'   => array(
                'separator' => '=',
                'value'     => intval($id)
            )
        );

        $result = $this->core->list_addressbook($q, 1);
        $address = $result[0];
        $form = $this->form_new_addressbook();
        $form['first_name']['value'] = $address['first_name'];
        $form['last_name']['value'] = $address['last_name'];
        $form['country']['value'] = $address['country'];
        $form['shipping_state']['value'] = $address['state_id'];
        $form['shipping_city']['value'] = $address['city_id'];
        $form['shipping_district']['value'] = $address['district_id'];
        $form['address_1']['value'] = $address['address_1'];
        $form['phone']['value'] = $address['phone'];
        $form['postcode']['value'] = $address['postcode'];
        $form['tag']['value'] = $address['tag'];

        return $form;
    }

    /**
     * TOPDROP_Form::get_fields
     * 
     * Foreach all fields
     * 
     * @return  HTML
     */
    public function get_fields($function = '')
    {
        if (!empty($function)) {
            $fields = call_user_func(array($this, $function));
            foreach ($fields as $key => $field_args) {
                woocommerce_form_field($key, $field_args['field'], $field_args['value']);
            }
        }
    }
}
