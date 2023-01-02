<?php

/**
 * Plugin Name: Extra product and service option to checkout for woocommerce
 * Plugin URI: http://mage-people.com
 * Description: Extra product and service option to checkout for woocommerce
 * Version: 1.0
 * Author: MagePeople Team
 * Author URI: http://www.mage-people.com/
 * Text Domain: extra-product-and-service
 * Domain Path: /languages/
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


class MEPS
{
    public function __construct()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        $this->define_constant();
        $this->load_dependancy();
    }

    public function define_constant()
    {
        define('MEPS_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('MEPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('MEPS_PLUGIN_FILE', plugin_basename(__FILE__));
        define('MEPS_TEXTDOMAIN', 'extra-product-and-service');
    }

    public function load_dependancy()
    {
        require_once MEPS_PLUGIN_DIR . 'inc/include.php';
    }

    // Get plugin data
    function meps_get_plugin_data($data)
    {
        $plugin_data = get_plugin_data(__FILE__);
        $meps_data = $plugin_data[$data];
        return $meps_data;
    }
}

$meps = new MEPS();