<?php

/**
 * Plugin Name:     Topbli Addressbook
 * Plugin URI:      https://www.cybersoftmedia.com
 * Description:     Topbli Addressbook is user dropship address database for topbli.com site
 * Version:         1.0.0
 * Author:          Hengky ST
 * Author URI:      https://www.cybersoftmedia.com
 * License:         GPL
 * Text Domain:     topdress
 */

if (!defined('ABSPATH')) {
    exit;
}

// constants.
define('TOPDRESS_VERSION', '1.0.0');
define('TOPDRESS_DBVERSION', '1.0.0');
define('TOPDRESS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TOPDRESS_PLUGIN_URI', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));

// load autoload.
require_once TOPDRESS_PLUGIN_PATH . 'autoload.php';

// set default timezone
date_default_timezone_set("Asia/Bangkok");

if (!class_exists('Topbli_Addressbook')) {

    /**
     * Class Topbli_Addressbook
     */
    class Topbli_Addressbook
    {

        /**
         * Constructor
         */
        public function __construct()
        {
            // validate required plugin
            if (!empty($this->requires = TOPDRESS_Helper::validate_plugins())) {
                add_action('admin_notices', array($this, 'show_notices'));
                return;
            }

            // Lets run our class
            new TOPDRESS_Admin();
            // new TOPDRESS_Woocommerce();
            // new TOPDRESS_Ajax();
            register_activation_hook(__FILE__, array($this, 'on_plugin_activation'));
        }

        /**
         * Show all notices
         */
        public function show_notices()
        {
            foreach ($this->requires as $notice) {
                echo '
				<div class="notice is-dismissible notice-error"><p>
					<b>Plugin Topbli Dropship not running</b>! ' . wp_kses_post($notice) . '
				</p></div>';
            }
        }

        /**
         * Actions when plugin activated
         */
        public function on_plugin_activation()
        {
            $version = get_option('topdress_version', '');
            $dbversion = get_option('topdress_dbversion', '');

            // install
            if (empty($version) || version_compare(TOPDRESS_VERSION, $version, '>')) {
                update_option('topdress_version', TOPDRESS_VERSION);
            }

            if (empty($dbversion) || version_compare(TOPDRESS_DBVERSION, $dbversion, '>')) {
                $this->create_table_address_book();
                update_option('topdress_dbversion', TOPDRESS_DBVERSION);
            }
        }

        private function create_table_address_book()
        {
            global $wpdb;
            $table_name = $wpdb->prefix . 'topdress_address_book';
            if ($wpdb->has_cap('collation')) {
                $charset_collate = $wpdb->get_charset_collate();
            }

            $sql = "DROP TABLE IF EXISTS $table_name;
                CREATE TABLE $table_name(
                id_address BIGINT NOT NULL AUTO_INCREMENT,
                id_user BIGINT NOT NULL,
                first_name VARCHAR(100) NULL DEFAULT NULL,
                last_name VARCHAR(100) NULL DEFAULT NULL,
                country VARCHAR(100) NULL DEFAULT NULL,
                state VARCHAR(100) NULL DEFAULT NULL,
                city VARCHAR(100) NULL DEFAULT NULL,
                district VARCHAR(100) NULL DEFAULT NULL,
                address_1 VARCHAR(500) NULL DEFAULT NULL,
                address_2 VARCHAR(500) NULL DEFAULT NULL,
                phone VARCHAR(20) NULL DEFAULT NULL,
                postcode INT(8) NULL DEFAULT NULL,
                tag VARCHAR(50) NULL DEFAULT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id_address)
			) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    // Let's Go bebs...!
    new Topbli_Addressbook();
}
