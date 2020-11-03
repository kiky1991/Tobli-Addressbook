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
define('TOPDRESS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TOPDRESS_PLUGIN_URI', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));

// load autoload.
require_once TOPDRESS_PLUGIN_PATH . 'autoload.php';

// load manager.
include_once TOPDRESS_PLUGIN_PATH . 'libs/topbli-addressbook-manager.php';

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
            // new TOPDROP_Admin();
            // new TOPDROP_Woocommerce();
            // new TOPDROP_Ajax();
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
            // doing something but not ready yet
        }
    }

    // Let's Go bebs...!
    new Topbli_Dropship();
}
