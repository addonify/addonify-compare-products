<?php
/**
 * Define settings fields for custom css.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/fields
 */

if ( ! function_exists( 'addonify_compare_products_custom_css_fields' ) ) {
	/**
	 * General setting fields for compare button displayed on product cataglog.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_custom_css_fields() {

		return array(
			'custom_css' => array(
				'type'           => 'textarea',
				'className'      => 'custom-css-box fullwidth',
				'inputClassName' => 'custom-css-textarea',
				'label'          => __( 'Custom CSS', 'addonify-compare-products' ),
				'description'    => __( 'If required, add your custom CSS code here.', 'addonify-compare-products' ),
				'placeholder'    => '#app { color: blue; }',
				'dependent'      => array( 'load_styles_from_plugin' ),
				'value'          => addonify_compare_products_get_option( 'custom_css' ),
			),
		);
	}
}


/**
 * Add setting fields into the global setting fields array.
 *
 * @since 1.0.0
 * @param mixed $settings_fields Setting fields.
 * @return array
 */
function addonify_compare_products_add_custom_css_fields_to_settings_fields( $settings_fields ) {

	return array_merge( $settings_fields, addonify_compare_products_custom_css_fields() );
}

add_filter( 'addonify_compare_products_settings_fields', 'addonify_compare_products_add_custom_css_fields_to_settings_fields' );
