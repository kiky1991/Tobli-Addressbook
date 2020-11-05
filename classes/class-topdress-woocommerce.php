<?php

/**
 * TOPDRRESS_Woocommerce class
 */
class TOPDRESS_Woocommerce
{

    /**
     * TOPDRESS_Woocommerce::__construct
     */
    public function __construct()
    {
        $this->core = new TOPDRESS_Core();
        
        add_action("wp_enqueue_scripts", array($this, 'register_assets'));
        add_filter('woocommerce_order_get_formatted_shipping_address', array($this, 'change_address'), 10, 3);
        add_action('woocommerce_after_edit_account_address_form', array($this, 'table_address'), 10, 1);
    }

    /**
     * TOPDROP_Woocommerce::register_assets
     * 
     * register assets
     * 
     * @return  file  
     */
    public function register_assets()
    {
        if (is_checkout()) {
            wp_enqueue_style('topdrop-checkout', TOPDROP_PLUGIN_URI . '/assets/css/checkout.css', '', TOPDROP_VERSION);
            wp_enqueue_script('topdrop-checkout', TOPDROP_PLUGIN_URI . "/assets/js/checkout.js", array('jquery'), TOPDROP_VERSION);
        }

        if (is_wc_endpoint_url('edit-address')) {
            wp_enqueue_style('topdrop-edit-address', TOPDROP_PLUGIN_URI . '/assets/css/edit-address.css', '', TOPDROP_VERSION);
        }
    }

    public function change_address($address, $raw_address, $order)
    {
        if (is_wc_endpoint_url('view-order')) {
            $address .= nl2br("\n(Customer)");
        }

        return $address;
    }

    public function table_address($array)
    {
        $q = array(
            'id_user'   => array(
                'separator' => '=',
                'value'     => get_current_user_id()
            )
        );

        $addresses = $this->core->list_addressbook($q);
        include_once TOPDRESS_PLUGIN_PATH . 'views/my-account-address.php';
    }
}
