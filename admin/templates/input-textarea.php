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
	'<textarea name="%1$s" id="%1$s" %2$s >%3$s</textarea>',
	esc_attr( $args['name'] ),
	wp_kses_post( $attr ),
	esc_html( $db_value )
);
