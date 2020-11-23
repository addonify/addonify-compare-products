<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.themebeez.com
 * @since             1.0.0
 * @package           Addonify_Compare_Products
 *
 * @wordpress-plugin
 * Plugin Name:       Addonify Compare Products
 * Plugin URI:        https://addonify.com/addonify-compare-products
 * Description:       <strong>Addonify Compare Products</strong> is an extension of WooCommerce plugin that adds products comparision functionality on your online store.
 * Version:           1.0.0
 * Author:            Addonify
 * Author URI:        http://www.themebeez.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       addonify-compare-products
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ADDONIFY_COMPARE_PRODUCTS_VERSION', '1.0.0' );
define( 'ADDONIFY_CP_DB_INITIALS', 'addonify_cp_' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-addonify-compare-products-activator.php
 */
function activate_addonify_compare_products() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-addonify-compare-products-activator.php';
	Addonify_Compare_Products_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-addonify-compare-products-deactivator.php
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