<?php

/**
 * Ajax Class
 */
class TOPDRESS_Ajax
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->core = new TOPDRESS_Core();
        $this->helper = new TOPDRESS_Helper();

        add_action('wp_ajax_topdress_addressbook_datatables', array($this, 'addressbook_datatables'));
        add_action('wp_ajax_topdress_search_customer', array($this, 'search_customer'));
        add_action('wp_ajax_topdress_view_address', array($this, 'view_address'));
        add_action('wp_ajax_topdress_delete_address', array($this, 'delete_address'));
        add_action('wp_ajax_topdress_set_default_address', array($this, 'set_default_address'));
        add_action('wp_ajax_topdress_search_address_term', array($this, 'search_address_term'));
        add_action('wp_ajax_topdress_checkout_load_addressbook', array($this, 'checkout_load_addressbook'));
    }

    /**
     * TOPDRESS_Ajax::addressbook_datatables
     * 
     * List addressbook datatables
     * @return   json
     */
    public function addressbook_datatables()
    {
        check_ajax_referer('topdress-addressbook-datatables-nonce', 'addressbook_datatables_nonce');
        $columns = array(
            0 => 'checkbox',
        );

        $user_id = get_current_user_id();
        if (!empty($_POST['search']['value'])) {
            $q = array(
                'id_user'   => array(
                    'separator' => '=',
                    'value'     => $user_id
                ),
                'first_name' => array(
                    'separator' => 'like',
                    'value'     => "%" . sanitize_text_field($_POST['search']['value']) . "%",
                ),
            );
        } else {
            $q = array(
                'id_user'   => array(
                    'separator' => '=',
                    'value'     => $user_id
                )
            );
        }


        $limit = sanitize_text_field(wp_slash($_POST['length']));
        $offset = sanitize_text_field(wp_slash($_POST['start']));
        $addresses = $this->core->list_addressbook($q, $limit, $offset);
        $total_address = $this->core->list_addressbook($q, 10000);
        $address_id = get_user_meta($user_id, 'topdress_address_id', true);

        if ($addresses) {
            $data = array();
            foreach ($addresses as $address) {
                $set_default = ($address_id !== $address['id_address']) ? ('<a id="set-address-book" address-id="' . $address['id_address'] . '">[Set as default]</a>') : '';
                $data[] = array(
                    $address['id_address'],
                    wp_sprintf('%1$s %2$s', __($address['first_name']), __($address['last_name'])),
                    $address['phone'],
                    $address['district'],
                    $address['city'],
                    $address['tag'],
                    '<a href="' . wc_get_page_permalink('myaccount') . 'edit-address/edit-addressbook?id=' . $address['id_address'] . '">[edit]</a>' . '&nbsp;' .
                        '<a id="delete-address-book" address-id="' . $address['id_address'] . '">[Delete]</a>&nbsp;' .
                        $set_default
                );
            }

            $json_data = array(
                "draw" => intval($_POST['draw']),
                "recordsTotal" => intval(count($total_address)),
                "recordsFiltered" => intval(count($total_address)),
                "data" => $data
            );
        } else {
            $json_data = array(
                "data" => array()
            );
        }

        wp_send_json($json_data);
    }

    /**
     * TOPDRESS_Ajax::search_customer
     * 
     * Search customer
     * @return   array
     */
    public function search_customer()
    {
        ob_start();

        // check_ajax_referer( 'search-customers', 'security' );

        if (!current_user_can('edit_shop_orders')) {
            wp_die(-1);
        }

        $term  = isset($_GET['search']) ? (string) wc_clean(wp_unslash($_GET['search'])) : '';
        $limit = 0;

        if (empty($term)) {
            wp_die();
        }

        $ids = array();
        // Search by ID.
        if (is_numeric($term)) {
            $customer = new WC_Customer(intval($term));

            // Customer does not exists.
            if (0 !== $customer->get_id()) {
                $ids = array($customer->get_id());
            }
        }

        // Usernames can be numeric so we first check that no users was found by ID before searching for numeric username, this prevents performance issues with ID lookups.
        if (empty($ids)) {
            $data_store = WC_Data_Store::load('customer');

            // If search is smaller than 3 characters, limit result set to avoid
            // too many rows being returned.
            if (3 > strlen($term)) {
                $limit = 20;
            }
            $ids = $data_store->search_customers($term, $limit);
        }

        $found_customers = array();

        foreach ($ids as $id) {
            $customer = new WC_Customer($id);
            /* translators: 1: user display name 2: user ID 3: user email */
            $found_customers[] = array(
                $customer->get_id(),
                sprintf(
                    /* translators: $1: customer name, $2 customer id, $3: customer email */
                    esc_html__('%1$s (#%2$s - %3$s)', 'woocommerce'),
                    $customer->get_first_name() . ' ' . $customer->get_last_name(),
                    $customer->get_id(),
                    $customer->get_email()
                )
            );
        }

        wp_send_json(apply_filters('woocommerce_json_search_found_customers', $found_customers));
    }

    public function view_address()
    {
        check_ajax_referer('topdress-view-address-nonce', 'topdress_nonce');

        if (!isset($_POST['id_address']) || empty($_POST['id_address'])) {
            wp_die(-1);
        }

        $id_address = sanitize_text_field(wp_unslash($_POST['id_address']));
        $result = $this->core->search_addressbook($id_address);

        if ($result) {
            ob_start();
            include_once TOPDRESS_PLUGIN_PATH . 'views/admin-view-addressbook.php';
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
        }

        wp_die();
    }

    public function delete_address()
    {
        check_ajax_referer('topdress-delete-address-nonce', 'topdress_delete_nonce');

        if (!isset($_POST['address_id']) || empty($_POST['address_id'])) {
            wp_die(-1);
        }

        $id_address = sanitize_text_field(wp_unslash($_POST['address_id']));
        $result = $this->core->delete_addressbook([$id_address]);

        wp_send_json(
            array(
                'success' => true,
                'message' => 'OK',
                'redirect' => wc_get_page_permalink('myaccount') . 'edit-address',
            )
        );
    }

    public function set_default_address()
    {
        check_ajax_referer('topdress-set-default-address-nonce', 'topdress_set_default_nonce');

        if (!isset($_POST['address_id']) || empty($_POST['address_id'])) {
            wp_die(-1);
        }

        $id_address = sanitize_text_field(wp_unslash($_POST['address_id']));
        $result = $this->core->search_addressbook($id_address);

        if (!$result) {
            wp_send_json(
                array(
                    'success' => false,
                    'message' => 'Cannot connect to database, try again.'
                )
            );
        }

        $user_id = get_current_user_id();
        if ($result['id_user'] != $user_id) {
            wp_send_json(
                array(
                    'success' => false,
                    'message' => 'Access denied'
                )
            );
        }

        update_user_meta($user_id, 'topdress_address_id', $result['id_address']);
        update_user_meta($user_id, 'topdress_address_tag', $result['tag']);
        update_user_meta($user_id, 'shipping_first_name', $result['first_name']);
        update_user_meta($user_id, 'shipping_last_name', $result['last_name']);
        update_user_meta($user_id, 'shipping_company', '');
        update_user_meta($user_id, 'shipping_country', $result['country']);
        update_user_meta($user_id, 'shipping_state_id', $result['state_id']);
        update_user_meta($user_id, 'shipping_state', $result['state']);
        update_user_meta($user_id, 'shipping_city_id', $result['city_id']);
        update_user_meta($user_id, 'shipping_city', $result['city']);
        update_user_meta($user_id, 'shipping_district_id', $result['district_id']);
        update_user_meta($user_id, 'shipping_district', $result['district']);
        update_user_meta($user_id, 'shipping_address_1', $result['address_1']);
        update_user_meta($user_id, 'shipping_address_2', '');
        update_user_meta($user_id, 'shipping_postcode', $result['postcode']);
        update_user_meta($user_id, 'shipping_phone', $result['phone']);

        wp_send_json(
            array(
                'success' => true,
                'message' => 'OK',
                'redirect' => wc_get_page_permalink('myaccount') . 'edit-address',
            )
        );
    }

    public function search_address_term()
    {
        check_ajax_referer('topdress-search-address-term-nonce', 'topdress_search_term_nonce');

        if (!isset($_POST['term'])) {
            wp_die(false);
        }

        $page = isset($_POST['page_id']) ? sanitize_text_field(wp_unslash($_POST['page_id'])) : 1;
        $term = sanitize_text_field(wp_unslash($_POST['term']));
        if (!empty($term) && strlen($term) < 3) {
            wp_die(false);
        }

        $user_id = get_current_user_id();
        if (!empty($term)) {
            $q = array(
                'id_user'   => array(
                    'separator' => '=',
                    'value'     => $user_id
                ),
                'first_name' => array(
                    'separator' => 'like',
                    'value'     => "%{$term}%"
                ),
            );
        } else {
            $q = array(
                'id_user'   => array(
                    'separator' => '=',
                    'value'     => $user_id
                )
            );
        }

        $address_id = get_user_meta($user_id, 'topdress_address_id', true);
        $addresses = $this->core->list_addressbook($q, 1);

        if (!empty($addresses)) {
            ob_start();
            include_once TOPDRESS_PLUGIN_PATH . 'views/checkout-li-addressbook.php';
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
        } else {
            echo '<p class="">Addressbook is empty!</p>';
        }

        wp_die();
    }

    public function checkout_load_addressbook()
    {
        check_ajax_referer('topdress-checkout-load-addressbook-nonce', 'topdress_checkout_load_addressbook_nonce');

        $page = isset($_POST['page_id']) ? sanitize_text_field(wp_unslash($_POST['page_id'])) : 1;
        $term = isset($_POST['term']) ? sanitize_text_field(wp_unslash($_POST['term'])) : '';
        $load_more = isset($_POST['more']) ? sanitize_text_field(wp_unslash($_POST['more'])) : 0;
        $user_id = get_current_user_id();
        if (!empty($term)) {
            $q = array(
                'id_user'   => array(
                    'separator' => '=',
                    'value'     => $user_id
                ),
                'first_name' => array(
                    'separator' => 'like',
                    'value'     => "%{$term}%"
                ),
            );
        } else {
            $q = array(
                'id_user'   => array(
                    'separator' => '=',
                    'value'     => $user_id
                )
            );
        }

        $limit = 1;
        $offset = ($limit * $page) - $limit;
        $addresses = $this->core->list_addressbook($q, $limit, $offset);
        $total = $this->core->list_addressbook($q, 10000);
        $get_paged = ceil(count($total) / $limit) - $page; 

        if ($addresses && $load_more == 1) {
            ob_start();
            include_once TOPDRESS_PLUGIN_PATH . 'views/checkout-li-addressbook.php';
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
        } else {
            ob_start();
            include_once TOPDRESS_PLUGIN_PATH . 'views/checkout-list-addressbook.php';
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
        }
        wp_die();
    }
}
