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

printf(
	'<input type="checkbox"  name="%1$s" id="%1$s" value="1" %2$s %3$s />',
	esc_attr( $args['name'] ),
	wp_kses_post( $is_checked ),
	wp_kses_post( $attr )
);
