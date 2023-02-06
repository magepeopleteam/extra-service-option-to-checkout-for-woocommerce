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
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main Class
 */
class MEPS {

	/**
	 * Constructor
	 */
	public function __construct() {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';

		$this->define_constant();
		$this->load_dependancy();
	}

	/**
	 * Define constant
	 */
	public function define_constant() {
		define( 'MEPS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'MEPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'MEPS_PLUGIN_FILE', plugin_basename( __FILE__ ) );
		define( 'MEPS_TEXTDOMAIN', 'extra-product-and-service' );
	}

	/**
	 * Load dependency
	 */
	public function load_dependancy() {
		require_once MEPS_PLUGIN_DIR . 'inc/include.php';
	}

	/**
	 * Get Plugin data
	 *
	 * @param string $data Plugin data.
	 */
	public function meps_get_plugin_data( $data ) {
		$plugin_data = get_plugin_data( __FILE__ );
		$meps_data   = $plugin_data[ $data ];
		return $meps_data;
	}
}

$meps = new MEPS();
