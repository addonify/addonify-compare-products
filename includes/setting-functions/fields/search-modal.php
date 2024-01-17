<?php
/**
 * Define settings fields for compare search modal.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/fields
 */

if ( ! function_exists( 'addonify_compare_products_search_modal_styles_fields' ) ) {
	/**
	 * Style setting fields for compare search modal.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function addonify_compare_products_search_modal_styles_fields() {

		return array(
			'search_modal_overlay_bck_color'              => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Overlay Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_overlay_bck_color' ),
			),
			'search_modal_bck_color'                      => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_bck_color' ),
			),
			'search_modal_add_btn_text_color'             => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add Button Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_text_color' ),
			),
			'search_modal_add_btn_text_color_hover'       => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add Button Label Color on Hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_text_color_hover' ),
			),
			'search_modal_add_btn_bck_color'              => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add Button Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color' ),
			),
			'search_modal_add_btn_bck_color_hover'        => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Add Button Background Color on Hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color_hover' ),
			),
			'search_modal_close_btn_text_color'           => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Close Button Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_text_color' ),
			),
			'search_modal_close_btn_text_color_hover'     => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Close Button On Hover Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_text_color_hover' ),
			),
			'search_modal_close_btn_bg_color'         => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Close Button Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_bg_color' ),
			),
			'search_modal_close_btn_bg_color_hover'   => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Close Button On Hover Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_bg_color_hover' ),
			),
			// @since 1.1.13
			'search_modal_product_title_color'            => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Product Title Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_product_title_color' ),
			),
			// @since 1.1.13
			'search_modal_product_separator_color'        => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Products Separator Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_product_separator_color' ),
			),
			// @since 1.1.13
			'search_modal_search_spinner_color'            => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Search Spinner Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_search_spinner_color' ),
			),
			// @since 1.1.13
			'search_modal_search_field_bg_color'          => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Search Field Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_search_field_bg_color' ),
			),
			// @since 1.1.13
			'search_modal_search_field_border_color'      => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Search Field Border Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_search_field_border_color' ),
			),
			// @since 1.1.13
			'search_modal_search_field_text_color'        => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Search Field Text Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_search_field_text_color' ),
			),
			// @since 1.1.13
			'search_modal_search_field_placeholder_color' => array(
				'type'          => 'color',
				'label'         => esc_html__( 'Search Field Placeholder Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_search_field_placeholder_color' ),
			),
		);
	}

	add_filter(
		'addonify_compare_products_settings_fields',
		function( $settings_fields ) {
			return array_merge( $settings_fields, addonify_compare_products_search_modal_styles_fields() );
		}
	);
}
