<?php
/**
 * Template for the admin part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin/templates
 */

/**
 * Template for the admin part of the plugin.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin/templates
 * @author     Addodnify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>
<div class="notice notice-error is-dismissible">
	<p><?php esc_html_e( 'Addonify Compare Products is enabled but not effective. This plugin requires WooCommerce plugin in order to work.', 'addonify-compare-products' ); ?></p>
</div>
