<?php
/**
 * Helper functions needed for plugins settings.
 *
 * @link       https://addonify.com/
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes/setting-functions/
 */

if ( ! function_exists( 'addonify_compare_products_get_pages' ) ) {
	/**
	 * Get all pages in array of page_id and page_title pairs.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	function addonify_compare_products_get_pages() {

		$pages = get_pages();

		$page_list = array();

		if ( ! empty( $pages ) ) {

			foreach ( $pages as $page ) {

				$page_list[ $page->ID ] = $page->post_title;
			}
		}

		return $page_list;
	}
}


if ( ! function_exists( 'addonify_compare_products_sanitize_multi_choices' ) ) {
	/**
	 * Sanitize multiple choices values.
	 *
	 * @since 1.0.7
	 * @param array $args Arguments.
	 * @return array $sanitized_values
	 */
	function addonify_compare_products_sanitize_multi_choices( $args ) {

		if (
			is_array( $args['choices'] ) &&
			count( $args['choices'] ) &&
			is_array( $args['values'] ) &&
			count( $args['values'] )
		) {

			$sanitized_values = array();

			foreach ( $args['values'] as $value ) {

				if ( array_key_exists( $value, $args['choices'] ) ) {

					$sanitized_values[] = $value;
				}
			}

			return $sanitized_values;
		}

		return array();
	}
}


if ( ! function_exists( 'addonify_compare_products_get_compare_button_icons' ) ) {
	/**
	 * Get the list of compare button icons.
	 *
	 * @since 1.0.7
	 * @return array
	 */
	function addonify_compare_products_get_compare_button_icons() {

		return apply_filters(
			'addonify_compare_products_compare_button_icons',
			array(
				'icon_one'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>',

				'icon_two'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 8H8.001L8 20H6V8H2l5-5 5 5zm10 8l-5 5-5-5h4V4h2v12h4z"/></svg>',

				'icon_three' => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 17 17"><g></g><path d="M8.94 6.871l1.081-1.34-0.004-0.003c0.855-0.971 2.087-1.528 3.378-1.528h1.898l-1.646-1.646 0.707-0.707 2.853 2.853-2.854 2.854-0.707-0.707 1.647-1.647h-1.898c-0.989 0-1.931 0.425-2.595 1.159l-1.080 1.339-0.78-0.627zM5.851 10.696l-0.011-0.008c-0.667 0.833-1.663 1.312-2.733 1.312h-3.107v1h3.107c1.369 0 2.645-0.611 3.503-1.676l0.011 0.009 0.941-1.166-0.777-0.629-0.934 1.158zM13.646 10.354l1.647 1.646h-1.898c-1.052 0-2.031-0.469-2.7-1.281l-4.269-5.265-0.010 0.008c-0.85-0.926-2.048-1.462-3.309-1.462h-3.107v1h3.107c0.998 0 1.948 0.428 2.611 1.17l4.161 5.132-0.005 0.004c0.86 1.076 2.143 1.694 3.52 1.694h1.898l-1.646 1.646 0.707 0.707 2.854-2.854-2.854-2.854-0.707 0.709z" /></svg>',

				'icon_four'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.17 18a3.001 3.001 0 0 1 5.66 0H22v2H11.83a3.001 3.001 0 0 1-5.66 0H2v-2h4.17zm6-7a3.001 3.001 0 0 1 5.66 0H22v2h-4.17a3.001 3.001 0 0 1-5.66 0H2v-2h10.17zm-6-7a3.001 3.001 0 0 1 5.66 0H22v2H11.83a3.001 3.001 0 0 1-5.66 0H2V4h4.17z"/></svg>',

				'icon_five'  => '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 17 17"><g></g><path d="M6 8h-6v-6h1v4.109c1.013-3.193 4.036-5.484 7.5-5.484 3.506 0 6.621 2.36 7.574 5.739l-0.963 0.271c-0.832-2.95-3.551-5.011-6.611-5.011-3.226 0.001-6.016 2.276-6.708 5.376h4.208v1zM11 9v1h4.208c-0.693 3.101-3.479 5.375-6.708 5.375-3.062 0-5.78-2.061-6.611-5.011l-0.963 0.271c0.952 3.379 4.067 5.739 7.574 5.739 3.459 0 6.475-2.28 7.5-5.482v4.108h1v-6h-6z"/></svg>',

				'icon_six'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M20.083 10.5l1.202.721a.5.5 0 0 1 0 .858L12 17.65l-9.285-5.571a.5.5 0 0 1 0-.858l1.202-.721L12 15.35l8.083-4.85zm0 4.7l1.202.721a.5.5 0 0 1 0 .858l-8.77 5.262a1 1 0 0 1-1.03 0l-8.77-5.262a.5.5 0 0 1 0-.858l1.202-.721L12 20.05l8.083-4.85zM12.514 1.309l8.771 5.262a.5.5 0 0 1 0 .858L12 13 2.715 7.429a.5.5 0 0 1 0-.858l8.77-5.262a1 1 0 0 1 1.03 0z"/></svg>',
			)
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_get_default_value_for_product_attributes_to_compare_setting' ) ) {
	/**
	 * Generate default values for `product_attributes_to_compare` setting.
	 *
	 * @since 1.1.9
	 */
	function addonify_compare_products_get_default_value_for_product_attributes_to_compare_setting() {

		$attributes = array();

		$wc_attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( $wc_attribute_taxonomies ) {

			foreach ( $wc_attribute_taxonomies as $wc_attribute_taxonomy ) {

				$attribute = array();

				$attribute['name']   = $wc_attribute_taxonomy->attribute_label;
				$attribute['id']     = $wc_attribute_taxonomy->attribute_id;
				$attribute['status'] = false;

				$attributes[] = $attribute;
			}
		}

		return $attributes;
	}
}


if ( ! function_exists( 'addonify_compare_products_get_all_product_attributes_ids' ) ) {
	/**
	 * Retrive all product attributes and return the array of attribute IDs.
	 *
	 * @since 1.1.9
	 */
	function addonify_compare_products_get_all_product_attributes_ids() {

		$attributes = array();

		$wc_attribute_taxonomies = wc_get_attribute_taxonomies();

		if ( $wc_attribute_taxonomies ) {

			foreach ( $wc_attribute_taxonomies as $wc_attribute_taxonomy ) {

				$attributes[ $wc_attribute_taxonomy->attribute_id ] = $wc_attribute_taxonomy->attribute_label;
			}
		}

		return $attributes;
	}
}


if ( ! function_exists( 'addonify_compare_products_sortable_setting_value' ) ) {
	/**
	 * Filters value for sortable controls.
	 * Checks for consistency of saved values.
	 *
	 * @since 1.1.9
	 *
	 * @param string $setting_id Setting ID.
	 * @return string
	 */
	function addonify_compare_products_sortable_setting_value( $setting_id ) {

		$saved_values = json_decode( addonify_compare_products_get_option( $setting_id ), true );

		$actual_values = addonify_compare_products_get_actual_values_of_sortable_setting( $setting_id );

		$filtered = array();
		$extras   = array();

		if ( empty( $actual_values ) ) {
			return wp_json_encode( $filtered );
		}

		if ( empty( $saved_values ) ) {
			$filtered = $actual_values;
			return wp_json_encode( $filtered );
		}

		$actual_values_count = count( $actual_values ) + 1;

		// If item in the saved value is present in the actual value, the add the item in the `filtered` array.
		foreach ( $saved_values as $saved_index => $saved_value ) {

			foreach ( $actual_values as $actual_index => $actual_value ) {

				if ( $saved_value['id'] === $actual_value['id'] ) {
					$filtered[ $saved_index ] = $saved_value;
				}
			}
		}

		// If a item in actual value is missing in the saved value, then add the item in the `filtered` array.
		foreach ( $actual_values as $actual_index => $actual_value ) {

			$in_array = false;

			foreach ( $saved_values as $saved_index => $saved_value ) {

				if ( $saved_value['id'] === $actual_value['id'] ) {
					$in_array = true;
				}
			}

			if ( ! $in_array ) {
				$filtered[ $actual_values_count ] = $actual_value;
				$actual_values_count++;
			}
		}

		// Returns all the values from the array and indexes the array numerically.
		$filtered = array_values( $filtered );

		return wp_json_encode( $filtered );
	}
}


if ( ! function_exists( 'addonify_compare_products_get_default_value_for_compare_table_fields_setting' ) ) {
	/**
	 * Generate default values for `compare_table_fields` setting.
	 *
	 * @since 1.1.9
	 */
	function addonify_compare_products_get_default_value_for_compare_table_fields_setting() {

		return apply_filters(
			'addonify_compare_products_filter_compare_table_fields_default',
			array(
				array(
					'name'   => esc_html__( 'Title', 'addonify-compare-products' ),
					'id'     => 'title',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Image', 'addonify-compare-products' ),
					'id'     => 'image',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Price', 'addonify-compare-products' ),
					'id'     => 'price',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Description', 'addonify-compare-products' ),
					'id'     => 'description',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Rating', 'addonify-compare-products' ),
					'id'     => 'rating',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Avaibility', 'addonify-compare-products' ),
					'id'     => 'in_stock',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Weight', 'addonify-compare-products' ),
					'id'     => 'weight',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Dimensions', 'addonify-compare-products' ),
					'id'     => 'dimensions',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Attributes', 'addonify-compare-products' ),
					'id'     => 'attributes',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Additional Information', 'addonify-compare-products' ),
					'id'     => 'additional_information',
					'status' => true,
				),
				array(
					'name'   => esc_html__( 'Add to Cart', 'addonify-compare-products' ),
					'id'     => 'add_to_cart_button',
					'status' => true,
				),
			)
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_get_compare_table_fields' ) ) {
	/**
	 * Define and return compare table fields.
	 *
	 * @since 1.1.9
	 */
	function addonify_compare_products_get_compare_table_fields() {

		return apply_filters(
			'addonify_compare_products_filter_compare_table_fields',
			array(
				'title'                  => esc_html__( 'Title', 'addonify-compare-products' ),
				'image'                  => esc_html__( 'Image', 'addonify-compare-products' ),
				'price'                  => esc_html__( 'Price', 'addonify-compare-products' ),
				'description'            => esc_html__( 'Description', 'addonify-compare-products' ),
				'rating'                 => esc_html__( 'Rating', 'addonify-compare-products' ),
				'in_stock'               => esc_html__( 'Avaibility', 'addonify-compare-products' ),
				'weight'                 => esc_html__( 'Weight', 'addonify-compare-products' ),
				'dimensions'             => esc_html__( 'Dimensions', 'addonify-compare-products' ),
				'attributes'             => esc_html__( 'Attributes', 'addonify-compare-products' ),
				'additional_information' => esc_html__( 'Additional Information', 'addonify-compare-products' ),
				'add_to_cart_button'     => esc_html__( 'Action', 'addonify-compare-products' ),
			)
		);
	}
}


if ( ! function_exists( 'addonify_compare_products_get_actual_values_of_sortable_setting' ) ) {
	/**
	 * Gets actual values of sortable setting.
	 *
	 * @since 1.1.9
	 *
	 * @param string $setting_id Setting ID.
	 */
	function addonify_compare_products_get_actual_values_of_sortable_setting( $setting_id ) {

		$actual_values = array();

		switch ( $setting_id ) {
			case 'compare_table_fields':
				$actual_values = addonify_compare_products_get_default_value_for_compare_table_fields_setting();
				break;
			case 'product_attributes_to_compare':
				$actual_values = addonify_compare_products_get_default_value_for_product_attributes_to_compare_setting();
				break;
			default:
		}

		return apply_filters( 'addonify_compare_products_filter_actual_values_of_sortable_setting', $actual_values, $setting_id );
	}
}
