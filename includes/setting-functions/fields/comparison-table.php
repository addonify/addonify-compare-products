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
			'compare_table_fields'                   => array(
				'label'       => __( 'Compare Table Fields', 'addonify-compare-products' ),
				'description' => __( 'Choose content that you want to display in comparison table. The position of the content are sortable if you wish to re-arrange how they are displayed.', 'addonify-compare-products' ),
				'type'        => 'sortable',
				'className'   => 'fullwidth',
				'choices'     => addonify_compare_products_get_compare_table_fields(),
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_sortable_setting_value( 'compare_table_fields' ),
			),
			'product_attributes_to_compare'          => array(
				'label'         => __( 'Products attributes to compare', 'addonify-compare-products' ),
				'description'   => __( 'Select product attributes that you want to compare. Remember to enable "Attributes" in the "Compare Table Fields" setting.', 'addonify-compare-products' ),
				'fallback_text' => __( 'You do not have product attributes.', 'addonify-compare-products' ),
				'type'          => 'sortable',
				'className'     => 'fullwidth',
				'choices'       => addonify_compare_products_get_all_product_attributes_ids(),
				'dependent'     => array( 'enable_product_comparison' ),
				'value'         => addonify_compare_products_sortable_setting_value( 'product_attributes_to_compare' ),
			),
			'display_comparison_table_fields_header' => array(
				'type'        => 'switch',
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
			// @since 1.1.13
			'comparison_modal_overlay_bg_color'            => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Overlay color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_overlay_bg_color' ),
			),
			// @since 1.1.13
			'comparison_modal_bg_color'                    => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Background color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_bg_color' ),
			),
			// @since 1.1.13
			'comparison_modal_txt_color'                   => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Text color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_txt_color' ),
			),
			// @since 1.1.13
			'comparison_modal_link_color'                  => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Link color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_link_color' ),
			),
			// @since 1.1.13
			'comparison_modal_link_hover_color'            => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Link color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_link_hover_color' ),
			),
			// @since 1.1.13
			'comparison_modal_header_txt_color'            => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Table header text color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_header_txt_color' ),
			),
			// @since 1.1.13
			'comparison_modal_header_bg_color'             => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Table header background color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_header_bg_color' ),
			),
			// @since 1.1.13
			'comparison_modal_remove_btn_bg_color'         => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product remove button background color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_remove_btn_bg_color' ),
			),
			// @since 1.1.13
			'comparison_modal_remove_btn_label_color'      => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product remove button label color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_remove_btn_label_color' ),
			),
			// @since 1.1.13
			'comparison_modal_remove_btn_bg_hover_color'   => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product remove button background color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_remove_btn_bg_hover_color' ),
			),
			// @since 1.1.13
			'comparison_modal_remove_btn_label_hover_color' => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product remove button label color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_remove_btn_label_hover_color' ),
			),
			// @since 1.1.13
			'comparison_modal_regular_price_color'         => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product regular price color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_regular_price_color' ),
			),
			// @since 1.1.13
			'comparison_modal_sale_price_color'            => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product sale price color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_sale_price_color' ),
			),
			// @since 1.1.13
			'comparison_modal_in_stock_txt_color'          => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product in stock label color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_in_stock_txt_color' ),
			),
			// @since 1.1.13
			'comparison_modal_out_of_stock_txt_color'      => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product out of stock label color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_out_of_stock_txt_color' ),
			),
			// @since 1.1.13
			'comparison_modal_add_to_cart_btn_bg_color'    => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add to cart button background color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_add_to_cart_btn_bg_color' ),
			),
			// @since 1.1.13
			'comparison_modal_add_to_cart_btn_label_color' => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add to cart button label color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_add_to_cart_btn_label_color' ),
			),
			// @since 1.1.13
			'comparison_modal_add_to_cart_btn_bg_hover_color' => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add to cart button background color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_add_to_cart_btn_bg_hover_color' ),
			),
			// @since 1.1.13
			'comparison_modal_add_to_cart_btn_label_hover_color' => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add to cart button label color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_add_to_cart_btn_label_hover_color' ),
			),
			// @since 1.1.13
			'comparison_modal_border_color'                => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Table border color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_border_color' ),
			),
			// @since 1.1.13
			'comparison_modal_close_btn_bg_color'          => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Modal close button background color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_close_btn_bg_color' ),
			),
			// @since 1.1.13
			'comparison_modal_close_btn_icon_color'        => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Modal close button icon color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_close_btn_icon_color' ),
			),
			// @since 1.1.13
			'comparison_modal_close_btn_bg_hover_color'    => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Modal close button background color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_close_btn_bg_hover_color' ),
			),
			// @since 1.1.13
			'comparison_modal_close_btn_icon_hover_color'  => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Modal close button icon color on hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'comparison_modal_close_btn_icon_hover_color' ),
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
