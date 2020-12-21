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

<div class="colorpicker-group">

	<?php
	if ( isset( $arg['label'] ) ) {
		printf(
			'<p>%s</p>',
			esc_html( $arg['label'] )
		);
	}

	printf(
		'<input type="text" value="%2$s" name="%1$s" id="%1$s" class="color-picker" data-alpha="true" />',
		esc_attr( $arg['name'] ),
		esc_attr( $db_value )
	);
	?>

</div>
