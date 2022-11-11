<?php
/**
 * Define settings fields for compare table displayed on compare modal and comparison page.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/fields
 */

if ( ! function_exists( 'addonify_compare_products_comparison_table_general_fields' ) ) {
	/**
	 * General setting fields for compare table displayed on compare modal and comparison page.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_comparison_table_general_fields() {

		return array(
			'fields_to_compare'                      => array(
				'label'       => __( 'Content to Display', 'addonify-compare-products' ),
				'description' => __( 'Choose content that you want to display in comparison table.', 'addonify-compare-products' ),
				'type'        => 'checkbox',
				'className'   => 'fullwidth',
				'choices'     => apply_filters(
					'addonify_compare_products_comparison_table_content_choices',
					array(
						'image'              => __( 'Image', 'addonify-compare-products' ),
						'title'              => __( 'Title', 'addonify-compare-products' ),
						'price'              => __( 'Price', 'addonify-compare-products' ),
						'rating'             => __( 'Rating', 'addonify-compare-products' ),
						'description'        => __( 'Description', 'addonify-compare-products' ),
						'in_stock'           => __( 'Stock Info', 'addonify-compare-products' ),
						'add_to_cart_button' => __( 'Add to Cart Button', 'addonify-compare-products' ),
					)
				),
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'fields_to_compare' ),
			),
			'display_comparison_table_fields_header' => array(
				'type'        => 'switch',
				'className'   => '',
				'label'       => __( 'Show Table Fields Header', 'addonify-compare-products' ),
				'description' => '',
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'display_comparison_table_fields_header' ),
			),
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_comparison_table_styles_fields' ) ) {
	/**
	 * Style setting fields for compare table displayed on compare modal and comparison page.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_comparison_table_styles_fields() {

		return array(
			'table_title_color'       => array(
				'type'          => 'color',
				'label'         => __( 'Table Title Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'table_title_color' ),
			),
			'table_title_color_hover' => array(
				'type'          => 'color',
				'label'         => __( 'Table Title Color on Hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'table_title_color' ),
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
function addonify_compare_products_add_comparison_table_fields_to_settings_fields( $settings_fields ) {

	$settings_fields = array_merge( $settings_fields, addonify_compare_products_comparison_table_general_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_compare_products_comparison_table_styles_fields() );

	return $settings_fields;
}
add_filter(
	'addonify_compare_products_settings_fields',
	'addonify_compare_products_add_comparison_table_fields_to_settings_fields'
);
