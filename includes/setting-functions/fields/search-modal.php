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
	 * @return array
	 */
	function addonify_compare_products_search_modal_styles_fields() {

		return array(
			'search_modal_overlay_bck_color'            => array(
				'type'          => 'color',
				'label'         => __( 'Overlay Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_overlay_bck_color' ),
			),
			'search_modal_bck_color'                    => array(
				'type'          => 'color',
				'label'         => __( 'Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_bck_color' ),
			),
			'search_modal_add_btn_text_color'           => array(
				'type'          => 'color',
				'label'         => __( 'Add Button Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_text_color' ),
			),
			'search_modal_add_btn_text_color_hover'     => array(
				'type'          => 'color',
				'label'         => __( 'Add Button Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_text_color_hover' ),
			),
			'search_modal_add_btn_bck_color'            => array(
				'type'          => 'color',
				'label'         => __( 'Add Button Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color' ),
			),
			'search_modal_add_btn_bck_color_hover'      => array(
				'type'          => 'color',
				'label'         => __( 'Add Button Background Color on Hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color_hover' ),
			),
			'search_modal_close_btn_text_color'         => array(
				'type'          => 'color',
				'label'         => __( 'Close Button Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_text_color' ),
			),
			'search_modal_close_btn_text_color_hover'   => array(
				'type'          => 'color',
				'label'         => __( 'Close Button On Hover Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_text_color_hover' ),
			),
			'search_modal_close_btn_border_color'       => array(
				'type'          => 'color',
				'label'         => __( 'Close Button Border Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_border_color' ),
			),
			'search_modal_close_btn_border_color_hover' => array(
				'type'          => 'color',
				'label'         => __( 'Close Button On Hover Border Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'search_modal_close_btn_border_color_hover' ),
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
function addonify_compare_products_add_search_modal_fields_to_settings_fields( $settings_fields ) {

	return array_merge( $settings_fields, addonify_compare_products_search_modal_styles_fields() );
}
add_filter( 'addonify_compare_products_settings_fields', 'addonify_compare_products_add_search_modal_fields_to_settings_fields' );
