<?php

/**
 * Helper function for this plugin.
 *
 * @link       http://www.themebeez.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 */

/**
 * Helper function for this plugin.
 *
 * @since      1.0.0
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/includes
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Compare_Products_Helpers {

	/**
	 * Default values for input fields in admin screen
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $default_input_values
	 */
	 protected $default_input_values;

	/**
	 * Check if woocommerce is active
	 *
	 * @since    1.0.0
	 */
	protected function is_woocommerce_active() {
		return ( class_exists( 'woocommerce' ) ) ? true : false;
	}


    /**
	 * This will create settings section, fields and register that settings in a database from the provided array data
	 *
	 * @since    1.0.0
	 * @param    array $args Data required for this plugin to work.
	 */
	protected function create_settings( $args ) {

		// define section ---------------------------.
		add_settings_section( $args['section_id'], $args['section_label'], $args['section_callback'], $args['screen'] );

		foreach ( $args['fields'] as $field ) {

			// create label.
			add_settings_field( $field['field_id'], $field['field_label'], $field['field_callback'], $args['screen'], $args['section_id'], $field['field_callback_args'] );

			foreach ( $field['field_callback_args'] as $sub_field ) {

				$this->default_input_values[ $sub_field['name'] ] = ( isset( $sub_field['default'] ) ) ? $sub_field['default'] : '';

				register_setting(
					$args['settings_group_name'],
					$sub_field['name'],
					array(
						'sanitize_callback' => isset( $sub_field['sanitize_callback'] ) ? $sub_field['sanitize_callback'] : 'sanitize_text_field',
					)
				);
			}
		}
	}


    /**
	 * Create comparision page if it was deleted by user.
	 *
	 * @since    1.0.0
	 * @param    string $string Page to check.
	 */
	public function check_if_comparision_page_exists( $string ) {

        die('check_if_comparision_page_exists called.');

		// if display type is "page" but page id is not present or page is deleted by user.
		// create new page and update database.

		// $display_type = isset( $_POST[ ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type' ] ) ? sanitize_text_field( wp_unslash( $_POST[ ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type' ] ) ) : '';

		// if ( 'page' === $display_type ) {

		// 	$page_id = get_option( ADDONIFY_CP_DB_INITIALS . 'page_id' );

		// 	if ( ! $page_id || 'publish' != get_post_status( $page_id ) ) {

		// 		require_once dirname( __FILE__, 2 ) . '/includes/class-addonify-compare-products-activator.php';

		// 		// generate new page.
		// 		Addonify_Compare_Products_Activator::activate();

		// 	}
		// }

		// return sanitize_text_field( $string );

	}


	/**
	 * Show custom "post status" label after page title in "Pages" admin menu.
	 * shows "Addonify Compare Page" label after the page title.
	 *
	 * @since    1.0.0
	 * @param array  $states Post Status.
	 * @param object $post  Post object.
	 */
	 public function display_custom_post_states_after_page_title( $states, $post ) {

		$compare_page_id = get_option( ADDONIFY_CP_DB_INITIALS . 'compare_page', get_option( ADDONIFY_CP_DB_INITIALS . 'page_id' ) );

		if ( get_post_type( $post->ID ) == 'page' && $post->ID == $compare_page_id ) {
			$states[] = __( 'Addonify Compare Page', 'addonify-wishlist' );
		}

		return $states;
	}


	// -------------------------------------------------
	// form helpers for admin setting screen
	// -------------------------------------------------


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
    public function text_box( $arguments ) {
		foreach ( $arguments as $args ) {
			$default = isset( $args['default'] ) ? $args['default'] : '';
			$db_value = get_option( $args['name'], $default );

			require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/input-textbox.php';
		}
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
	// public function overlay_btn_offset_group( $arguments ) {
	// 	require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/overlay_btn_offset_group.php';
	// }


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
	public function text_area( $arguments ) {
		foreach ( $arguments as $args ) {
			$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
			$db_value = get_option( $args['name'], $placeholder );
			$attr = isset( $args['attr'] ) ? $args['attr'] : '';

			require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/input-textarea.php';
		}
	}

	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
	public function toggle_switch( $arguments ) {
		foreach ( $arguments as $args ) {
			$args['attr'] = ' class="lc_switch"';
			$this->checkbox( $args );
		}
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $args Data required to make this function work.
	 */
	public function color_picker_group( $args ) {
		foreach ( $args as $arg ) {
			$default = isset( $arg['default'] ) ? $arg['default'] : '';
			$db_value = ( get_option( $arg['name'] ) ) ? get_option( $arg['name'] ) : $default;

			require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/input-colorpicker.php';
		}
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $args Data required to make this function work.
	 */
	public function checkbox_with_label( $args ) {
		foreach ( $args as $arg ) {
			require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/checkbox-group.php';
		}
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $args Data required to make this function work.
	 */
	public function checkbox( $args ) {
		$default_state = ( array_key_exists( 'default', $args ) ) ? $args['default'] : '';
		$db_value = get_option( $args['name'], $default_state );
		$is_checked = ( $db_value ) ? 'checked' : '';
		$attr = ( array_key_exists( 'attr', $args ) ) ? $args['attr'] : '';

		require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/input-checkbox.php';
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
	public function select( $arguments ) {
		foreach ( $arguments as $args ) {

			$default = isset( $args['default'] ) ? $args['default'] : '';

			$db_value = get_option( $args['name'], $default );
			$options = ( array_key_exists( 'options', $args ) ) ? $args['options'] : array();

			require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/input-select.php';
		}
	}


	/**
	 * Select Page field
	 *
	 * @since    1.0.0
	 * @param string $arguments Arguments required to generate this field.
	 */
	public function select_page( $arguments ) {

		$options = array();

		foreach ( get_pages() as $page ) {
			$options[ $page->ID ] = $page->post_title;
		}

		$args = $arguments[0];
		
		$db_value = get_option( $args['name'] );

		if ( empty( $db_value ) ) {
			$db_value = $args['default'];
		}

		if ( $db_value != $args['default'] ) {
			$args['end_label'] = 'Please insert "[addonify_compare_products]" shortcode into the content area of the page';
		}

		require ADDONIFY_CP_PLUGIN_PATH . '/admin/templates/input-select.php';
	}

}
