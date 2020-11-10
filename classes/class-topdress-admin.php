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
                wp_enqueue_script('topdrop-shop-order', TOPDRESS_PLUGIN_URI . '/assets/js/shop-order.js', array('jquery'), TOPDRESS_VERSION, true);
            }

            if ($this->validate_screen('users_page_topdress-address-book')) {
                wp_enqueue_style('topdress-admin-address-book', TOPDRESS_PLUGIN_URI . '/assets/css/admin-address-book.css', '', TOPDRESS_VERSION);
                wp_enqueue_script('topdress-admin-address-book', TOPDRESS_PLUGIN_URI . "/assets/js/admin-address-book.js", array('jquery'), TOPDRESS_PLUGIN_URI, true);
                wp_enqueue_style('select2_css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css');
                wp_register_script('select2_js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery'), '4.0.3', true);
                wp_enqueue_script('select2_js');
                wp_localize_script(
                    'topdress-admin-address-book',
                    'topdress',
                    array(
                        'url' => admin_url('admin-ajax.php'),
                        'nonce' => wp_create_nonce('topdress-view-address-nonce'),
                    )
                );
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

            if (isset($_POST['action']) && $_POST['action'] == 'bulk_address_delete' && !empty($_POST['address'])) {
                $result = $this->core->delete_addressbook($_POST['address']);

                if (!$result) {
                    $this->display_flash_notices('Cannot delete addressbook, try again!', 'error');
                } else {
                    $this->display_flash_notices('Success delete addressbook!');
                }
            }

            $id_user = '';
            if (isset($_POST['search_customer_address']) && isset($_POST['topdress_user_id']) && !empty($_POST['topdress_user_id'])) {
                $id_user = sanitize_text_field(wp_unslash($_POST['topdress_user_id']));
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
