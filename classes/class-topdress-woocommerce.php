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
        add_action("wp_enqueue_scripts", array($this, 'register_assets'));
        add_filter('woocommerce_order_get_formatted_shipping_address', array($this, 'change_address'), 10, 3);
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
    }

    public function change_address($address, $raw_address, $order)
    {
        if (is_wc_endpoint_url('view-order')) {
            $address .= nl2br("\n(Customer)");
        }

        return $address;
    }
}
