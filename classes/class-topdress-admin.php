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
            $this->helper   = new TOPDRESS_Helper();
            $this->core     = new TOPDRESS_Core();

            // assets
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'), 10);

            // hook
            add_action('admin_menu', array($this, 'admin_menu'), 10);
            add_filter('woocommerce_admin_billing_fields', array($this, 'custom_admin_billing_fields'), 99);
            add_filter('woocommerce_admin_shipping_fields', array($this, 'custom_admin_shipping_fields'), 99);

            // // notices
            add_action('admin_notices', array($this, 'display_flash_notices'), 12);
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
         * TOPDRESS_Admin::admin_menu
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

        /**
         * Render page addressboook
         *
         * @return HTML
         */
        public function render_page_addressbook()
        {
            include_once TOPDRESS_PLUGIN_PATH . '/classes/includes/list-addressbook.php';

            $search = "";
            if (isset($_POST['s']) && !empty($_POST['s'])) {
                $search = sanitize_text_field(wp_unslash($_POST['s']));
            }

            if (isset($_POST['action']) && $_POST['action'] == 'bulk_address_delete' && !empty($_POST['address'])) {
                $result = $this->core->delete_addressbook($_POST['address']);

                if (!$result) {
                    $this->display_flash_notices('Cannot delete addressbook, try again!', 'error');
                } else {
                    $this->display_flash_notices('Success delete addressbook!');
                }
            }

            $list_address = new List_Addressbook();
            include_once TOPDRESS_PLUGIN_PATH . 'views/admin-users-addressbook.php';
        }

        /**
         * TOPDRESS_Admin::add_flash_notice
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
         * TOPDRESS_Admin::display_flash_notices
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
