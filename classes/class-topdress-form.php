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
        return apply_filters('form_new_addressbook', array(
            'first_name' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('First Name', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-first')
                ),
                'value' => ''
            ),
            'last_name' => array(
                'field' => array(
                    'type'        => 'text',
                    'label'       => __('Last Name', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-last')
                ),
                'value' => ''
            ),
            'city' => array(
                'field' => array(
                    'type'        => 'checkbox',
                    'label'       => __('Town / City', 'topdress'),
                    'placeholder' => __('', 'topdress'),
                    'required'    => true,
                    'class'       => array('input-checkbox', 'woocommerce-form-row', 'form-row-wide')
                ),
                'value' => ''
            ),
            'district' => array(
                'field' => array(
                    'type'        => 'tel',
                    'label'       => __('Phone', 'topdrop'),
                    'placeholder' => __('', 'topdrop'),
                    'required'    => true,
                    'class'       => array('form-row', 'form-row-last')
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
