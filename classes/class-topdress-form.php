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

        return apply_filters('form_new_addressbook', array(
            'first_name' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('First Name', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-first'),
                    'autocomplete' => 'given-name'
                ),
                'value' => ''
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
                'value' => ''
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
                'value' => ''
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
                'value' => ''
            ),
            'shipping_district' => array(
                'field' => array(
                    'type'        => 'select',
                    'label'       => __('District', 'topdress'),
                    'placeholder' => __('Select District', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-wide', 'update_totals_on_change', 'address-field', 'init-select2'),
                    'options'   => array('kalsd'),
                ),
                'value' => ''
            ),
            'address_1' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Address', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-wide')
                ),
                'value' => ''
            ),
            'phone' => array(
                'field' => array(
                    'type'        => 'tel',
                    'label'       => __('Phone', 'topdrop'),
                    'placeholder' => __('', 'topdrop'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-last')
                ),
                'value' => ''
            ),
            'postcode' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Postcode', 'topdrop'),
                    'placeholder' => __('', 'topdrop'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-last'),
                    'validate'  => array('postcode')
                ),
                'value' => ''
            ),
            'tag' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Tag', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row-wide')
                ),
                'value' => ''
            ),
        ));
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
