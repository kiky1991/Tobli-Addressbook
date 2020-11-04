<?php

if (!class_exists('TOPDRESS_Helper')) {

    /**
     * Helper Class
     */
    class TOPDRESS_Helper
    {

        /**
         * Minimum PHP version required
         *
         * @var string
         */
        private $min_php = '7.0';

        /**
         * Constructor
         */
        public function __construct()
        {
            // hati2 ada function dibawah validate plugins
        }

        /**
         * Check if woocommerce is active
         *
         * @return boolean Is active.
         */
        public function is_woocommerce_active()
        {
            return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true);
        }

        /**
         * Check if the PHP version is supported
         *
         * @return bool
         */
        public function is_supported_php()
        {
            if (version_compare(PHP_VERSION, $this->min_php, '<=')) {
                return false;
            }

            return true;
        }

        /**
         * Validate require plugins
         * 
         * @return boolean
         */
        public static function validate_plugins()
        {
            $error = array();

            if (!(new self)->is_supported_php()) {
                $error[] = 'Minimum PHP Required for this plugin ' . (new self)->min_php;
            }

            if (!(new self)->is_woocommerce_active()) {
                $error[] = 'Plugin Woocommerce is not active, install and active first!';
            }

            return $error;
        }

        /**
         * Get address ids from order
         *
         * @param  integer $order_id Order ID.
         * @param  string  $type     Address type.
         * @return integer           Address id.
         */
        public function get_address_id_from_order($order_id = 0, $type = 'billing_state')
        {
            if (!in_array($type, array('billing_country', 'billing_state', 'billing_city', 'billing_district', 'shipping_country', 'shipping_state', 'shipping_city', 'shipping_district'), true)) {
                return 0;
            }
            $id = get_post_meta($order_id, '_' . $type . '_id', true);
            if ('' === $id) {
                $id = get_post_meta($order_id, '_' . $type, true);
            }
            return !in_array($type, array('billing_country', 'shipping_country')) ? intval($id) : $id;
        }
    }
}
