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

        add_action('init', array($this, 'add_pages'));
        add_action('woocommerce_account_edit-address/add-addressbook_endpoint', array($this, 'endpoint_content'));
        add_action("wp_enqueue_scripts", array($this, 'register_assets'));
        add_filter('woocommerce_order_get_formatted_shipping_address', array($this, 'change_address'), 10, 3);
        add_action('woocommerce_after_edit_account_address_form', array($this, 'table_address'), 10, 1);
        add_action('template_redirect', array($this, 'save_address'));
    }

    public function add_pages()
    {
        add_rewrite_endpoint('edit-address/add-addressbook', EP_ROOT | EP_PAGES);
        flush_rewrite_rules();
    }

    public function endpoint_content()
    {
        $form = new TOPDRESS_Form();
        include_once TOPDRESS_PLUGIN_PATH . 'views/my-account-add-addressbook.php';
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
        if (is_wc_endpoint_url('edit-address') || wc_get_page_permalink('myaccount') . 'edit-address/add-addressbook') {
            wp_enqueue_style('topdress-edit-address', TOPDRESS_PLUGIN_URI . '/assets/css/edit-address.css', '', TOPDRESS_VERSION);
            wp_enqueue_script('topdress-edit-address', TOPDRESS_PLUGIN_URI . "/assets/js/edit-address.js", array('jquery'), TOPDRESS_VERSION, true);
            wp_localize_script(
                'topdress-edit-address',
                'topdress',
                array(
                    'url' => admin_url('admin-ajax.php'),

                )
            );
            wp_localize_script(
                'topdress-edit-address',
                'nonce',
                array(
                    'delete' => wp_create_nonce('topdress-delete-address-nonce'),
                    'set_default' => wp_create_nonce('topdress-set-default-address-nonce'),
                    'search_term' => wp_create_nonce('topdress-search-address-term-nonce'),
                )
            );
        }
    }

    public function change_address($address, $raw_address, $order)
    {
        if (is_wc_endpoint_url('view-order')) {
            $address .= nl2br("\n(Customer)");
        }

        return $address;
    }

    /**
     * Fix name formatting on myaccount page
     *
     * @param  array  $address     Address data.
     * @param  int    $customer_id Customer ID.
     * @param  string $name        Billing/Shipping.
     * @return array               Address data.
     */
    public function format_myaccount_address($address, $customer_id, $name)
    {
        $address['tag'] = 'rumah';
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

        $user_id = get_current_user_id();
        $address_id = get_user_meta($user_id, 'topdress_address_id', true);
        $addresses = $this->core->list_addressbook($q);
        include_once TOPDRESS_PLUGIN_PATH . 'views/my-account-address.php';
    }

    /**
     * TOPDRESS_Woocommerce::save_address
     * 
     * Save address form
     * @access  public
     * 
     * @return  html
     */
    public function save_address()
    {
        $nonce_value = wc_get_var($_REQUEST['topdress_save_address_nonce'], wc_get_var($_REQUEST['_wpnonce'], '')); // @codingStandardsIgnoreLine.

        if (!wp_verify_nonce($nonce_value, 'topdress_save_address')) {
            return;
        }

        if (empty($_POST['action']) || 'topdress_save_address' !== $_POST['action']) {
            return;
        }

        wc_nocache_headers();

        $user_id = get_current_user_id();
        if ($user_id <= 0) {
            return;
        }

        $required = ['first_name' => 'First Name', 'last_name' => 'Last Name', 'shipping_state' => 'State', 'shipping_city' => 'City', 'shipping_district' => 'District', 'address_1' => 'Address', 'phone' => 'Phone', 'postcode' => 'Post Code', 'tag' => 'Tag Address'];
        $errors = array();
        foreach (array_keys($_POST) as $post) {
            if (empty($_POST[$post]) && in_array($post, array_keys($required))) {
                $errors[] = $required[$post];
            }
        }

        if (!empty($errors)) {
            wc_add_notice(__(implode(', ', $errors) . ' is a required field', 'topdress'), 'error');
            wp_safe_redirect(wc_get_page_permalink('myaccount') . 'edit-address/add-addressbook');
            exit;
        }

        $data = array(
            'id_user'   => get_current_user_id(),
            'first_name' => isset($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '',
            'last_name' => isset($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '',
            'country' => isset($_POST['country']) ? sanitize_text_field(wp_unslash($_POST['country'])) : '',
            'state_id' => isset($_POST['shipping_state']) ? sanitize_text_field(wp_unslash($_POST['shipping_state'])) : 0,
            'state' => isset($_POST['shipping_state_name']) ? sanitize_text_field(wp_unslash($_POST['shipping_state_name'])) : '',
            'city_id' => isset($_POST['shipping_city']) ? sanitize_text_field(wp_unslash($_POST['shipping_city'])) : 0,
            'city' => isset($_POST['shipping_city_name']) ? sanitize_text_field(wp_unslash($_POST['shipping_city_name'])) : '',
            'district_id' => isset($_POST['shipping_district']) ? sanitize_text_field(wp_unslash($_POST['shipping_district'])) : 0,
            'district' => isset($_POST['shipping_district_name']) ? sanitize_text_field(wp_unslash($_POST['shipping_district_name'])) : '',
            'address_1' => isset($_POST['address_1']) ? sanitize_text_field(wp_unslash($_POST['address_1'])) : '',
            'address_2' => '',
            'phone' => isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '',
            'postcode' => isset($_POST['postcode']) ? sanitize_text_field(wp_unslash($_POST['postcode'])) : 0,
            'tag' => isset($_POST['tag']) ? sanitize_text_field(wp_unslash($_POST['tag'])) : '',
        );

        $result = $this->core->update_addressbook($data);
        if ($result) {
            wc_add_notice(__('Shipping address has been save.', 'topdress'));
        } else {
            wc_add_notice(__('Cannot save shipping address, call your administrator.', 'topdress'), 'error');
        }

        wp_safe_redirect(wc_get_page_permalink('myaccount') . 'edit-address/add-addressbook');
        exit;
    }
}
