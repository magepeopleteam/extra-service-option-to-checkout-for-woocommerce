<?php
if (!defined('ABSPATH')) {
    die;
} // Cannot access pages directly.


require_once MEPS_PLUGIN_DIR . 'inc/class-helper.php';
require_once MEPS_PLUGIN_DIR . 'src/class-admin-setting.php';
require_once MEPS_PLUGIN_DIR . 'src/class-checkout-service.php';
require_once MEPS_PLUGIN_DIR . 'src/class-product-service.php';

add_action('admin_enqueue_scripts', 'meps_add_admin_scripts', 10, 1);
if (!function_exists('meps_add_admin_scripts')) {
    function meps_add_admin_scripts($hook)
    {
        wp_enqueue_style('meps-admin-style', plugin_dir_url(__DIR__) . 'assets/css/admin-setting.css', array());
        wp_enqueue_script('meps-admin-script', plugin_dir_url(__DIR__) . '/assets/js/admin-setting.js', array(), time(), true);
        wp_localize_script('meps-admin-script', 'meps_php_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'MEPS_PLUGIN_URL' => MEPS_PLUGIN_URL,
        ));
    }
}