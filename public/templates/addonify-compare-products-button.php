<?php
/**
 * Template for the front end part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates
 */

/**
 * Template for the front end part of the plugin.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates
 * @author     Addodnify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;

printf(
	'<button type="button" class="addonify-cp-button button %s" data-product_id="%s" >%s</button>',
	esc_attr( $css_class ),
	esc_attr( $product_id ),
	esc_html( $label )
);
