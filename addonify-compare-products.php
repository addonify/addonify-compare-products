<?php
/**
 * Addonify Compare Products
 *
 * @link              https://addonify.com/
 * @since             1.0.0
 * @package           Addonify_Compare_Products
 *
 * @wordpress-plugin
 * Plugin Name:       Addonify - Compare Products For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/addonify-compare-products/
 * Description:       Addonify Compare Products is a WooCommerce extension that allows website visitors to compare multiple products on your online store.
 * Version:           1.1.8
 * Author:            Addonify
 * Author URI:        https://addonify.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       addonify-compare-products
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ADDONIFY_COMPARE_PRODUCTS_VERSION', '1.1.8' );
define( 'ADDONIFY_CP_DB_INITIALS', 'addonify_cp_' );
define( 'ADDONIFY_CP_PLUGIN_PATH', dirname( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_addonify_compare_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addonify-compare-products-activator.php';
	Addonify_Compare_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_addonify_compare_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addonify-compare-products-deactivator.php';
	Addonify_Compare_Products_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_addonify_compare_products' );
register_deactivation_hook( __FILE__, 'deactivate_addonify_compare_products' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-addonify-compare-products.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_addonify_compare_products() {

	$plugin = new Addonify_Compare_Products();
	$plugin->run();

}
run_addonify_compare_products();
