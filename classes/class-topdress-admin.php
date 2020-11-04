<?php

if (!class_exists('TOPDRESS_Admin')) {

    /**
     * Admin Class
     */
    class TOPDRESS_Admin
    {

        /**
         * Constructor
         */
        public function __construct()
        {
            add_action('admin_menu', array($this, 'admin_menu'), 10);

            // order page
            add_filter('woocommerce_admin_billing_fields', array($this, 'custom_admin_billing_fields'), 99);
            add_filter('woocommerce_admin_shipping_fields', array($this, 'custom_admin_shipping_fields'), 99);

            // assets
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 10);

            // // woocommerce settings
            // add_filter('woocommerce_account_settings', array($this, 'add_settings'));

            // // user profile
            // add_action('show_user_profile', array($this, 'show_form_dropship'), 30, 1);
            // add_action('edit_user_profile', array($this, 'show_form_dropship'), 30, 1);
            // add_action('personal_options_update', array($this, 'save_form_dropship'), 1);
            // add_action('edit_user_profile_update', array($this, 'save_form_dropship'), 1);

            // // admin order details form
            // add_action('woocommerce_admin_order_data_after_order_details', array($this, 'admin_order_detail'), 12, 1);
            // add_action('woocommerce_process_shop_order_meta', array($this, 'save_admin_order_detail'), 12, 1);

            // // notices
            // add_action('admin_notices', array($this, 'display_flash_notices'), 12);

            $this->helper      = new TOPDRESS_Helper();
        }

        /**
         * Validate current admin screen
         *
         * @param   string  $page   page to validate 
         * @return  boolean         Screen is Brandplus or not.
         */
        public function validate_screen($page = '')
        {
            $screen = get_current_screen();
            if (is_null($screen)) {
                return false;
            }

            if (!empty($page) && $screen->id === $page) {
                return true;
            }

            return false;
        }

        /**
         * Enqueue Script Inventory Manager
         */
        public function enqueue_scripts()
        {
            if ($this->validate_screen('shop_order')) {
                wp_enqueue_style('topdress-shop-order', TOPDRESS_PLUGIN_URI . '/assets/css/shop-order.css', '', TOPDRESS_VERSION);
            }
        }

        /**
         * TOPDROP_Admin::admin_menu
         * 
         * Admin menu
         * 
         * @return  void    
         */
        public function admin_menu()
        {
            add_users_page(
                __('Shipping Address Book', 'topdress'),
                __('Shipping Address Book', 'topdress'),
                'manage_options',
                'topdress-address-book',
                array($this, 'render_page_addressbook'),
                40
            );
        }

        /**
         * Custom admin billing fields
         *
         * @param  array $fields Billing fields.
         * @return array         Custom billing fields.
         */
        public function custom_admin_billing_fields($fields)
        {
            return $fields;
        }

        /**
         * Custom admin shipping fields
         *
         * @param  array $fields Shipping fields.
         * @return array         Custom shipping fields.
         */
        public function custom_admin_shipping_fields($fields)
        {
            unset($fields['email']);

            return $fields;
        }

        public function render_page_addressbook()
        {
            include_once TOPDRESS_PLUGIN_PATH . '/classes/includes/list-addressbook.php';

            $search = "";
            if (isset($_POST['s']) && !empty($_POST['s'])) {
                $search = sanitize_text_field(wp_unslash($_POST['s']));
                var_dump($search);
                die;
            }

            if (isset($_POST['action']) && $_POST['action'] == 'bulk_addressbook_delete') {
                // $this->core->update_redeem($_POST['address']);
            }

            // prepare get the list balance
            $list_address = new List_Addressbook();

            include_once TOPDRESS_PLUGIN_PATH . 'views/admin-users-addressbook.php';
        }

        /**
         * TOPDROP_Admin::add_flash_notice
         * 
         * Add notice
         * @param   string  $message  Message
         * @param   string  $type     Error type
         * @param   string  $p        Pharagraph
         * 
         * @return  void 
         */
        protected function add_flash_notice($message = '', $type = 'success', $p = true)
        {
            $old_notice = get_option('my_flash_notices', array());
            $old_notice[] = array(
                'type'      => !empty($type) ? $type : 'success',
                'message'   => $p ? '<p>' . $message . '</p>' : $message,
            );
            update_option('my_flash_notices', $old_notice, false);
        }

        /**
         * TOPDROP_Admin::display_flash_notices
         * 
         * Display notice
         * 
         * @return  HTML 
         */
        public function display_flash_notices()
        {
            $notices = get_option('my_flash_notices', array());
            foreach ($notices as $notice) {
                printf(
                    '<div class="notice is-dismissible notice-%1$s">%2$s</div>',
                    esc_attr($notice['type']),
                    wp_kses_post($notice['message'])
                );
            }

            if (!empty($notices)) {
                delete_option("my_flash_notices", array());
            }
        }
    }
}
