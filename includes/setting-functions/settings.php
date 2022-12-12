<?php
/**
 * Load and register plugin settings.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/
 */

/**
 * Load general setting fields.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/general.php';

/**
 * Load general setting fields for compare button displayed on product catalog.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/compare-button.php';

/**
 * Load general setting fields for compare dock or floating compare bar. 
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/floating-compare-bar.php';

/**
 * Load general setting fields for products comparison modal.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/add-to-compare-modal.php';

/**
 * Load general setting fields for products search modal.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/search-modal.php';

/**
 * Load general setting fields for products comparison table.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/comparison-table.php';

/**
 * Load general setting fields for custom css.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'setting-functions/fields/custom-css.php';


/**
 * Define default values for plugin's setting options.
 * 
 * @since 1.0.0
 * @return array $defaults
 */
if ( ! function_exists( 'addonify_compare_products_settings_defaults' ) ) {

	function addonify_compare_products_settings_defaults( $setting_id = '' ) {

		$defaults = apply_filters( 
			'addonify_compare_products_setting_defaults',  
			array(
				// Settings
				'enable_product_comparison'                    => true,
				'compare_products_btn_position'                => 'after_add_to_cart',
				'compare_products_btn_show_icon'               => true,
				'compare_products_btn_icon'                    => 'icon_one',
				'compare_products_btn_label'                   => __( 'Compare', 'addonify-compare-products' ),
				'compare_products_btn_icon_position'           => 'left',
				'compare_products_display_type'                => 'popup',
				'compare_page'                                 => '',
				'compare_products_cookie_expires'              => 30,
				'display_comparison_table_fields_header'       => true,
				'fields_to_compare'                            => json_encode( array( 'image', 'title', 'price', 'add_to_cart_button', 'rating', 'description' ) ),
				'load_styles_from_plugin'                      => false,

				// Design - add to compare button
				'compare_btn_text_color'                       => '#FFFFFF',
				'compare_btn_text_color_hover'                 => '#FFFFFF',
				'compare_btn_bck_color'                        => '#444444',
				'compare_btn_bck_color_hover'                  => '#3765FA',

				// Design - Floating dock bar
				'floating_bar_bck_color'                       => '#02030E',
				'floating_bar_text_color'                      => 'rgba(255, 255, 255, 0.7)',
				'floating_bar_add_button_text_color'           => '#FFFFFF',
				'floating_bar_add_button_text_color_hover'     => '#FFFFFF',
				'floating_bar_add_button_bck_color'            => '#343434',
				'floating_bar_add_button_bck_color_hover'      => '#3765FA',
				'floating_bar_compare_button_text_color'       => '#444444',
				'floating_bar_compare_button_text_color_hover' => '#FFFFFF',
				'floating_bar_compare_button_bck_color'        => '#FFFFFF',
				'floating_bar_compare_button_bck_color_hover'  => '#3765FA',

				// Design - search modal

				'search_modal_overlay_bck_color'               => 'rgba(0, 0, 0, 0.8)',
				'search_modal_bck_color'                       => '#FFFFFF',

				'search_modal_add_btn_text_color'              => '#444444',
				'search_modal_add_btn_text_color_hover'        => '#444444',
				'search_modal_add_btn_bck_color'               => '#EEEEEE',
				'search_modal_add_btn_bck_color_hover'         => '#D4D4D4',

				'search_modal_close_btn_text_color'            => 'rgba(255, 255, 255, 0.7)',
				'search_modal_close_btn_text_color_hover'      => 'rgba(255, 255, 255, 1)',
				'search_modal_close_btn_border_color'          => 'rgba(255, 255, 255, 0.3)',
				'search_modal_close_btn_border_color_hover'    => 'rgba(255, 255, 255, 1)',

				// Design - Table

				'table_title_color'                            => '#444444',
				'table_title_color_hover'                      => '#3765FA',

				// Design - Custom CSS
				'custom_css'                                   => ''
			)
		);

		return ( $setting_id && isset( $defaults[ $setting_id ] ) ) ? $defaults[ $setting_id ] : $defaults;
	}
}


if ( ! function_exists( 'addonify_compare_products_get_option' ) ) {
	/**
	 * Get plugin's setting option's value.
	 * 
	 * @since 1.0.0
	 * @return mixed
	 */
	function addonify_compare_products_get_option( $setting_id ) {

		return get_option( ADDONIFY_CP_DB_INITIALS . $setting_id, addonify_compare_products_settings_defaults( $setting_id ) );
	}
}


if ( ! function_exists( 'addonify_compare_products_update_settings' ) ) {
	/**
	 * Update plugin's setting options' values.
	 * 
	 * Checks the type of each setting options, sanitizes the value and updates the option's value.
	 * 
	 * @since 1.0.0
	 * @param array $settings array of options values.
	 * @return boolean true on successful update else false.
	 */
	function addonify_compare_products_update_settings( $settings = '' ) {

		if ( 
			is_array( $settings ) &&
			count( $settings ) > 0
		) {
			$setting_fields = addonify_compare_products_settings_fields();

			foreach ( $settings as $id => $value ) {

				$sanitized_value = null;

				$setting_type = $setting_fields[$id]['type'];

				switch ( $setting_type ) {
					case 'text':
						$sanitized_value = sanitize_text_field( $value );
						break;
					case 'textarea':
						$sanitized_value = sanitize_textarea_field( $value );
						break;
					case 'switch':
						$sanitized_value = ( $value == true ) ? '1' : '0';
						break;
					case 'number':
						$sanitized_value = (int) $value;
						break;
					case 'color':
						$sanitized_value = sanitize_text_field( $value );
						break;
					case 'select':
						$setting_choices = $setting_fields[$id]['choices'];
						$sanitized_value = ( array_key_exists( $value, $setting_choices ) ) ? sanitize_text_field( $value ) : $setting_choices[0];
						break;
					case 'checkbox':
						$sanitize_args = array(
							'choices' => $settings_fields[$key]['choices'],
							'values'  => $value
						);
						$sanitized_value = addonify_compare_products_sanitize_multi_choices( $sanitize_args );
						$sanitized_value = json_encode( $value );                     
						break;
					default:
						$sanitized_value = sanitize_text_field( $value );
				}

				if ( ! update_option( ADDONIFY_CP_DB_INITIALS . $id, $sanitized_value ) ) {
					return false;
				}
			}

			return true;
		}        
	}
}



if ( ! function_exists( 'addonify_compare_products_get_settings_values' ) ) {
	/**
	 * Get plugin's all setting options values.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_get_settings_values() {

		if ( addonify_compare_products_settings_defaults() ) {

			$settings_values = array();

			$setting_fields = addonify_compare_products_settings_fields();

			foreach ( addonify_compare_products_settings_defaults() as $id => $value ) {

				$setting_type = $setting_fields[$id]['type'];

				switch ( $setting_type ) {
					case 'switch':
						$settings_values[$id] = ( addonify_compare_products_get_option( $id ) == '1' ) ? true : false;
						break;
					case 'checkbox':
						$settings_values[$id] = json_decode( addonify_compare_products_get_option( $id ), true );
						break;
					case 'number':
						$settings_values[$id] = addonify_compare_products_get_option( $id );
						break;
					default:
						$settings_values[$id] = addonify_compare_products_get_option( $id );
				}
			}

			return $settings_values;
		}
	}
}





if ( ! function_exists( 'addonify_compare_products_settings_fields' ) ) {
	/**
	 * Get plugin's all settings fields.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_settings_fields() {

		return apply_filters( 'addonify_compare_products_settings_fields', array() );
	}
}


if ( ! function_exists( 'addonify_compare_products_get_settings_fields' ) ) {
	/**
	 * Define settings sections and respective settings fields.
	 * 
	 * @since 1.0.7
	 * @return array
	 */
	function addonify_compare_products_get_settings_fields() {

		return array(
			'settings_values' => addonify_compare_products_get_settings_values(),
			'tabs'            => array(
				'settings' => array(
					'title'    => __( 'Settings', 'addonify-compare-products' ),
					'sections' => array(
						'general'          => array(
							'title'       => __( 'General Options', 'addonify-compare-products' ),
							'description' => '',
							'fields'      => addonify_compare_products_general_setting_fields(),
						),
						'compare_button'   => array(
							'title'       => __('Compare Button Options', 'addonify-compare-products' ),
							'description' => '',
							'fields'      => addonify_compare_products_compare_button_general_fields(),
						),
						'comparison_table' => array(
							'title'       => __( 'Comparison Table', 'addonify-compare-products' ),
							'description' => '',
							'fields'      => addonify_compare_products_comparison_table_general_fields()
						)
					)
				),
				'styles'   => array(
					'sections' => array(
						'general'                => array(
							'title'       => __( 'General', 'addonify-compare-products' ),
							'description' => '',
							'fields'      => addonify_compare_products_styles_settings_fields(),
						),
						'compare_button_colors'  => array(
							'title'       => __( 'Compare Button Colors', 'addonify-compare-products' ),
							'description' => '',
							'type'        => 'color-options-group',
							'dependent'   => array('load_styles_from_plugin'),
							'fields'      => addonify_compare_products_compare_button_styles_fields()
						),
						'floating_bar_colors'    => array(
							'title'       => __( 'Floating Dock Colors', 'addonify-compare-products' ),
							'description' => '',
							'type'        => 'color-options-group',
							'dependent'   => array('load_styles_from_plugin'),
							'fields'      => addonify_compare_products_floating_bar_styles_fields()
						),
						'search_modal_color'     => array(
							'title'       => __( 'Search Modal Colors', 'addonify-compare-products' ),
							'description' => '',
							'type'        => 'color-options-group',
							'dependent'   => array('load_styles_from_plugin'),
							'fields'      => addonify_compare_products_search_modal_styles_fields()
						),
						'comparison_table_color' => array(
							'title'       => __( 'Comparison Table Colors', 'addonify-compare-products' ),
							'description' => '',
							'type'        => 'color-options-group',
							'dependent'   => array('load_styles_from_plugin'),
							'fields'      => addonify_compare_products_comparison_table_styles_fields()
						),
						'custom_css'             => array(
							'title'       => __( 'Developer', 'addonify-compare-products' ),
							'description' => '',
							'fields'      => addonify_compare_products_custom_css_fields()
						)
					)
				),
				'products' => array(
					'recommended' => array(
						// Recommend plugins here.
						'content' => __( 'Coming soon....', 'addonify-compare-products' ),
					)
				),
			),
		);
	}
}
