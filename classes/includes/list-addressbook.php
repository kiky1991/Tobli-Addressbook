
<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

if (!class_exists('List_Addressbook')) {
    class List_Addressbook extends WP_List_Table
    {
        public $status;

        public function __construct()
        {
            $this->status = array(
                'active'    => 'Active Code',
                'expired'   => 'Expired',
                'novalid'   => 'Not Valid',
            );

            parent::__construct(
                array(
                    'singular' => 'Addressbook',
                    'plural'   => 'Addressbooks',
                    'ajax'     => true
                )
            );
        }

        public function prepare_items($id_user = "")
        {
            global $wpdb;

            // set table name
            $table_name = $wpdb->prefix . 'topdress_address_book';

            // set offset perpage
            $per_page = 20;

            // get columns of table
            $columns = $this->get_columns();

            // get hidden column if needed 
            $hidden = $this->get_hidden_columns();

            // get sortable columns if needed
            $sortable = $this->get_sortable_columns();

            // set header
            $this->_column_headers = array($columns, $hidden, $sortable);

            // setup offset with order by
            $paged = $this->get_pagenum();
            $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($sortable))) ? array_search($_REQUEST['orderby'], $sortable) : 'created_at';
            $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
            $offset = ($per_page * $paged) - $per_page;

            // init for where
            $where = "";

            // if have search term
            if (!empty($id_user)) {
                $where = "WHERE id_user = $id_user";
            }

            // count id from table to get max page
            $total_items = $wpdb->get_var("SELECT COUNT(id_address) FROM $table_name $where");

            // get the items data from table DB and passed to array
            $this->items = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table_name
                    $where
		            ORDER BY $orderby $order LIMIT %d OFFSET %d",
                    $per_page,
                    $offset
                ),
                ARRAY_A
            );

            // set pagination
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page' => $per_page,
                'total_pages' => ceil($total_items / $per_page)
            ));
        }

        /**
         * Display tablenav.
         *
         * Adds an export button.
         *
         * @param  string $which Whether we're generating the "top" or "bottom" tablenav.
         */
        protected function display_tablenav($which)
        {
            if ('top' === $which) {
                include_once TOPDRESS_PLUGIN_PATH . '/classes/includes/tablenav-addressbook.php';
            }
        }

        public function get_columns()
        {
            return [
                'cb'            => "<input type='checkbox' />",
                'id_address'    => "ID Address",
                'first_name'    => "First Name",
                'last_name'     => "Last Name",
                'phone'         => "Phone",
                'district'      => "District",
                'city'          => "City",
                'tag'           => "Address Tag",
                'action'        => "Actions",
            ];
        }

        public function column_cb($item)
        {
            return sprintf("<input type='checkbox' name='address[]' value='%s' />", $item['id_address']);
        }

        public function column_default($item, $column_name)
        {
            switch ($column_name) {
                case 'id_address':
                case 'first_name':
                case 'last_name':
                case 'phone':
                case 'district':
                case 'city':
                case 'tag':
                    return $item[$column_name];
                    break;
                case 'action':
                    return '<a class="topdress-view-address" address-id="' . $item['id_address'] . '">[view]</a>';
                    break;
                default:
                    return "no value";
            }
        }

        public function get_bulk_actions()
        {
            return array(
                'bulk_address_delete' => 'Bulk Delete',
            );
        }

        public function get_sortable_columns()
        {
            return array(
                'created_at'    => ['Create Date', false],
            );
        }

        public function get_hidden_columns()
        {
            return array(
                'id_address'
            );
        }
    }
}
