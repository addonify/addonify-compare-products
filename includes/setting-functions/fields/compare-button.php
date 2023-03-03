<?php
/**
 * Define settings fields for compare button displayed on product catalog.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/fields
 */

if ( ! function_exists( 'addonify_compare_products_compare_button_general_fields' ) ) {
	/**
	 * General setting fields for compare button displayed on product cataglog.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_compare_button_general_fields() {

		return array(
			'compare_products_btn_position'      => array(
				'type'        => 'select',
				'className'   => '',
				'label'       => __( 'Button Position', 'addonify-compare-products' ),
				'description' => __( 'Choose where to place the compare button.', 'addonify-compare-products' ),
				'choices'     => array(
					'after_add_to_cart'  => __( 'After Add to Cart Button', 'addonify-compare-products' ),
					'before_add_to_cart' => __( 'Before Add to Cart Button', 'addonify-compare-products' ),
				),
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'compare_products_btn_position' ),
			),
			'compare_products_btn_label'         => array(
				'type'        => 'text',
				'className'   => '',
				'label'       => __( 'Button Label', 'addonify-compare-products' ),
				'description' => __( 'Label for compare button.', 'addonify-compare-products' ),
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'compare_products_btn_label' ),
			),
			'compare_products_btn_show_icon'     => array(
				'type'        => 'switch',
				'className'   => '',
				'label'       => __( 'Show Icon', 'addonify-compare-products' ),
				'description' => __( 'Show icon on compare button.', 'addonify-compare-products' ),
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'compare_products_btn_show_icon' ),
			),
			'compare_products_btn_icon'          => array(
				'type'          => 'radio',
				'typeStyle'     => 'radioIcon', // Not used on Front-End Control. Only for Ref!
				'renderChoices' => 'html',
				'className'     => 'fullwidth radio-input-group hide-label svg-icons-choices',
				'label'         => __( 'Select Icon', 'addonify-compare-products' ),
				'description'   => __( 'Select icon to be displayed on compare button.', 'addonify-compare-products' ),
				'choices'       => addonify_compare_products_get_compare_button_icons(),
				'dependent'     => array( 'enable_product_comparison', 'compare_products_btn_show_icon' ),
				'value'         => addonify_compare_products_get_option( 'compare_products_btn_icon' ),
			),
			'compare_products_btn_icon_position' => array(
				'type'        => 'select',
				'className'   => '',
				'label'       => __( 'Icon Position', 'addonify-compare-products' ),
				'description' => __( 'Choose position for icon in the compare button.', 'addonify-compare-products' ),
				'choices'     => array(
					'left'  => __( 'Before Button Label', 'addonify-compare-products' ),
					'right' => __( 'After Button Label', 'addonify-compare-products' ),
				),
				'dependent'   => array( 'enable_product_comparison' ),
				'value'       => addonify_compare_products_get_option( 'compare_products_btn_icon_position' ),
			),
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_compare_button_styles_fields' ) ) {
	/**
	 * Style setting fields for compare button displayed on product cataglog.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_compare_button_styles_fields() {

		return array(
			'compare_btn_text_color'       => array(
				'type'          => 'color',
				'label'         => __( 'Label Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'compare_btn_text_color' ),
			),
			'compare_btn_text_color_hover' => array(
				'type'          => 'color',
				'label'         => __( 'Label Color on Hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'compare_btn_text_color_hover' ),
			),
			'compare_btn_bck_color'        => array(
				'type'          => 'color',
				'label'         => __( 'Background Color', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'compare_btn_bck_color' ),
			),
			'compare_btn_bck_color_hover'  => array(
				'type'          => 'color',
				'label'         => __( 'Background Color on Hover', 'addonify-compare-products' ),
				'isAlphaPicker' => true,
				'className'     => '',
				'value'         => addonify_compare_products_get_option( 'compare_btn_bck_color_hover' ),
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
function addonify_compare_products_add_compare_button_fields_to_settings_fields( $settings_fields ) {

	$settings_fields = array_merge( $settings_fields, addonify_compare_products_compare_button_general_fields() );

	$settings_fields = array_merge( $settings_fields, addonify_compare_products_compare_button_styles_fields() );

	return $settings_fields;
}
add_filter(
	'addonify_compare_products_settings_fields',
	'addonify_compare_products_add_compare_button_fields_to_settings_fields'
);
