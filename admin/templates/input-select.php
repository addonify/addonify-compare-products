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

echo '<select  name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['name'] ) . '" >';

foreach ( $options as $value => $label ) {

	echo '<option value="' . esc_attr( $value ) . '" ';

	if ( $db_value === $value ) {
		echo 'selected';
	}

	echo ' >' . esc_html( $label ) . '</option>';
}

echo '</select>';
