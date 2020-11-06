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

        public function get_admin_edit_user_link($user_id)
        {
            if (get_current_user_id() == $user_id)
                $edit_link = get_edit_profile_url($user_id);
            else
                $edit_link = add_query_arg('user_id', $user_id, self_admin_url('user-edit.php'));

            return $edit_link;
        }
    }
}
