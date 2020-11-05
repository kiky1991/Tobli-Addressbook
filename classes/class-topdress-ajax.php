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

        add_action('wp_ajax_topdress_search_customer', array($this, 'search_customer'));
        add_action('wp_ajax_topdress_view_address', array($this, 'view_address'));
        add_action('wp_ajax_topdress_delete_address', array($this, 'delete_address'));
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
        check_ajax_referer('topdress-delete-address-nonce', 'topdress_nonce');

        if (!isset($_POST['address_id']) || empty($_POST['address_id'])) {
            wp_die(-1);
        }

        $id_address = sanitize_text_field(wp_unslash($_POST['address_id']));
        $result = $this->core->delete_addressbook([$id_address]);

        $q = array(
            'id_user'   => array(
                'separator' => '=',
                'value'     => get_current_user_id()
            )
        );

        $addresses = $this->core->list_addressbook($q);
        if ($result) {
            ob_start();
            include_once TOPDRESS_PLUGIN_PATH . 'views/table-list-address-book.php';
            $content = ob_get_contents();
            ob_end_clean();
            echo $content;
        }

        wp_die();
    }
}
