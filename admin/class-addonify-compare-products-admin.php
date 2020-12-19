<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and other required variables.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin
 * @author     Adodnify <info@addonify.com>
 */
class Addonify_Compare_Products_Admin {

	/**
	 * Settings page slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $settings_page_slug    Default settings page slug for this plugin
	 */
	private $settings_page_slug = 'addonify_compare_products';


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;


	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if ( is_admin() ) {

			// if display type is page but page id is not present or page is deleted by user.
			// change display type to popup.

			if ( 'page' === get_option( 'addonify_cp_compare_products_display_type' ) ) {
				$page_id = get_option( 'addonify_cp_page_id' );

				if ( ! $page_id || 'publish' != get_post_status( $page_id ) ) {
					update_option( 'addonify_cp_compare_products_display_type', 'popup' );
				}
			}
		}

	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $wp_styles;

		// load styles in this plugin page only.
		if ( isset( $_GET['page'] ) && $this->settings_page_slug === $_GET['page'] ) {

			// toggle switch.
			wp_enqueue_style( 'lc_switch', plugin_dir_url( __FILE__ ) . 'css/lc_switch.css', array(), $this->version );

			// built in wp color picker.
			// requires atleast wordpress 3.5.
			wp_enqueue_style( 'wp-color-picker' );

			// admin css.
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addonify-compare-products-admin-min.css', array(), $this->version );
		}

		if ( ! isset( $wp_styles->registered['addonify-icon-fix'] ) ) {

			// admin menu icon fix.
			wp_enqueue_style( 'addonify-icon-fix', plugin_dir_url( __FILE__ ) . 'css/addonify-icon-fix.css', array(), $this->version );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// load scripts in plugin page only.
		if ( isset( $_GET['page'] ) && $this->settings_page_slug === $_GET['page'] ) {

			if ( isset( $_GET['tabs'] ) && 'styles' === $_GET['tabs'] ) {
				// requires atleast wordpress 4.9.0.
				wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

				wp_register_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version );

				$color_picker_strings = array(
					'clear' => __( 'Clear', 'addonify-compare-products' ),
					'clearAriaLabel' => __( 'Clear color', 'addonify-compare-products' ),
					'defaultString' => __( 'Default', 'addonify-compare-products' ),
					'defaultAriaLabel' => __( 'Select default color', 'addonify-compare-products' ),
					'pick' => __( 'Select Color', 'addonify-compare-products' ),
					'defaultLabel' => __( 'Color value', 'addonify-compare-products' ),
				);

				wp_localize_script( 'wp-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );
				wp_enqueue_script( 'wp-color-picker-alpha' );

			}

			// toggle switch.
			wp_enqueue_script( 'lc_switch', plugin_dir_url( __FILE__ ) . 'js/lc_switch.min.js', array( 'jquery' ), $this->version, false );

			// use wp-color-picker-alpha as dependency.
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addonify-compare-products-admin-min.js', array( 'jquery' ), $this->version, false );

		}

	}


	/**
	 * Check if woocommerce is active
	 *
	 * @since    1.0.0
	 */
	private function is_woocommerce_active() {
		return ( class_exists( 'woocommerce' ) ) ? true : false;
	}



	/**
	 * Generate admin menu for this plugin
	 *
	 * @since    1.0.0
	 */
	public function add_menu_callback() {

		// do not show menu if woocommerce is not active.
		if ( ! $this->is_woocommerce_active() ) {
			return;
		}

		global $menu;
		$parent_menu_slug = null;

		foreach ( $menu as $item ) {
			if ( 'addonify' === strtolower( $item[0] ) ) {

				$parent_menu_slug = $item[2];
				break;
			}
		}

		if ( ! $parent_menu_slug ) {
			add_menu_page( __( 'Addonify Settings', 'addonify-compare-products' ), 'Addonify', 'manage_options', $this->settings_page_slug, array( $this, 'get_settings_screen_contents' ), plugin_dir_url( __FILE__ ) . '/templates/addonify-logo.svg', 76 );

			add_submenu_page( $this->settings_page_slug, __( 'Addonify Compare Products Settings', 'addonify-compare-products' ), __( 'Compare', 'addonify-compare-products' ), 'manage_options', $this->settings_page_slug, array( $this, 'get_settings_screen_contents' ), 1 );

		} else {

			// sub menu.
			// redirects to main plugin link.
			add_submenu_page( $parent_menu_slug, __( 'Addonify Compare Products Settings', 'addonify-compare-products' ), __( 'Compare', 'addonify-compare-products' ), 'manage_options', $this->settings_page_slug, array( $this, 'get_settings_screen_contents' ), 1 );

		}
	}



	/**
	 * Print "settings" link in plugins.php admin page
	 *
	 * @since    1.0.0
	 * @param    string $links Links.
	 * @param    string $file  PLugin file name.
	 */
	public function custom_plugin_link_callback( $links, $file ) {

		if ( plugin_basename( dirname( __FILE__, 2 ) . '/addonify-compare-products.php' ) === $file ) {

			// add "Settings" link.
			$links[] = '<a href="admin.php?page=' . $this->settings_page_slug . '">' . __( 'Settings', 'addonify-compare-products' ) . '</a>';
		}

		return $links;
	}



	/**
	 * Get contents from settings page templates and print it
	 *
	 * @since    1.0.0
	 */
	public function get_settings_screen_contents() {
		$current_tab = ( isset( $_GET['tabs'] ) ) ? sanitize_text_field( wp_unslash( $_GET['tabs'] ) ) : 'settings';
		$tab_url = "admin.php?page=$this->settings_page_slug&tabs=";

		require_once dirname( __FILE__ ) . '/templates/settings-screen.php';
	}



	/**
	 * Generate form elements for settings page from array
	 *
	 * @since    1.0.0
	 */
	public function settings_page_ui() {

		// ---------------------------------------------
		// General Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'compare_products_settings',
			'section_id' => 'general_options',
			'section_label' => __( 'GENERAL OPTIONS', 'addonify-compare-products' ),
			'section_callback' => '',
			'screen' => $this->settings_page_slug . '-settings',
			'fields' => array(
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'enable_product_comparision',
					'field_label' => __( 'Enable Product Comparision', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'enable_product_comparision',
							'checked' => 1,
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_position',
					'field_label' => __( 'Compare Button Position', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_position',
							'options' => array(
								'before_add_to_cart' => __( 'Before Add To Cart Button', 'addonify-compare-products' ),
								'after_add_to_cart' => __( 'After Add To Cart Button', 'addonify-compare-products' ),
								'overlay_on_image' => __( 'Overlay On The Product Image', 'addonify-compare-products' ),
							),
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_label',
					'field_label' => __( 'Compare Button Label', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'text_box' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_label',
							'default' => __( 'Compare', 'addonify-compare-products' ),
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type',
					'field_label' => __( 'Display Comparision in', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type',
							'options' => array(
								'popup' => __( 'Popup Modal', 'addonify-compare-products' ),
								'page' => __( 'Comparision Page', 'addonify-compare-products' ),
							),
							'sanitize_callback' => array( $this, 'check_if_comparision_page_exists' ),
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'compare_products_cookie_expires',
					'field_label' => __( 'Cookies Expire', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'compare_products_cookie_expires',
							'options' => array(
								'browser' => __( 'After Browser Close', 'addonify-compare-products' ),
								'1' => __( '1 Day', 'addonify-compare-products' ),
								'7' => __( '1 Week', 'addonify-compare-products' ),
								'14' => __( '2 Weeks', 'addonify-compare-products' ),
								'21' => __( '3 Weeks', 'addonify-compare-products' ),
								'28' => __( '4 Weeks', 'addonify-compare-products' ),
							),
						),
					),
				),
			),
		);

		// create settings fields.
		$this->create_settings( $settings_args );

		// ---------------------------------------------
		// Contents Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'compare_products_settings',
			'section_id' => 'table_options',
			'section_label' => __( 'TABLE OPTIONS', 'addonify-compare-products' ),
			'section_callback' => '',
			'screen' => $this->settings_page_slug . '-settings-table-options',
			'fields' => array(
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'fields_to_compare',
					'field_label' => __( 'Fields To Compare', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'checkbox_with_label' ),
					'field_callback_args' => array(
						array(
							'label' => __( 'Product Image', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_product_image',
							'type' => 'number',
						),
						array(
							'label' => __( 'Product Title', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_product_title',
							'type' => 'number',
						),
						array(
							'label' => __( 'Product Rating', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_product_rating',
							'type' => 'number',
						),
						array(
							'label' => __( 'Product Price', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_product_price',
							'type' => 'number',
						),
						array(
							'label' => __( 'Product Excerpt', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_product_excerpt',
							'type' => 'number',
						),
						array(
							'label' => __( 'Stock Info', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_stock_info',
							'type' => 'number',
						),
						array(
							'label' => __( 'Attributes', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_attributes',
							'type' => 'number',
						),
						array(
							'label' => __( 'Add To Cart Button', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'show_add_to_cart_btn',
							'type' => 'number',
						),
					),
				),
			),
		);

		// create settings fields.
		$this->create_settings( $settings_args );

		// ---------------------------------------------
		// Styles Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'compare_products_styles',
			'section_id' => 'style_options',
			'section_label' => __( 'STYLE OPTIONS', 'addonify-compare-products' ),
			'section_callback' => '',
			'screen' => $this->settings_page_slug . '-styles',
			'fields' => array(
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'load_styles_from_plugin',
					'field_label' => __( 'Load Styles From Plugin', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'toggle_switch' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'load_styles_from_plugin',
							'checked' => 0,
							'sanitize_callback' => 'sanitize_textarea_field',
						),
					),
				),
			),
		);

		// create settings fields.
		$this->create_settings( $settings_args );

		// ---------------------------------------------
		// Content Colors
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name' => 'compare_products_styles',
			'section_id' => 'content_colors',
			'section_label' => __( 'CONTENT COLORS', 'addonify-compare-products' ),
			'section_callback' => '',
			'screen' => $this->settings_page_slug . '-content-colors',
			'fields' => array(
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'compare_btn_bck_color',
					'field_label' => __( 'Compare Button', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'color_picker_group' ),
					'field_callback_args' => array(
						array(
							'label' => __( 'Text Color', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'compare_btn_text_color',
							'default' => '#000000',
						),
						array(
							'label' => __( 'Background Color', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'compare_btn_bck_color',
							'default' => '#eeeeee',
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'modal_box_color',
					'field_label' => __( 'Modal Box', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'color_picker_group' ),
					'field_callback_args' => array(
						array(
							'label' => __( 'Overlay Background Color', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'modal_overlay_bck_color',
							'default' => '#000000',
						),
						array(
							'label' => __( 'Background Color', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'modal_bck_color',
							'default' => '#ffffff',
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'table_title_color',
					'field_label' => __( 'Table Title', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'color_picker_group' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'table_title_color',
							'default' => '#000000',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'close_btn_color',
					'field_label' => __( 'Close Button', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'color_picker_group' ),
					'field_callback_args' => array(
						array(
							'label' => __( 'Text Color', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'close_btn_text_color',
							'default' => '#d3ced2',
						),
						array(
							'label' => __( 'Background Color', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'close_btn_bck_color',
							'default' => '#f5c40e',
						),
						array(
							'label' => __( 'Text Color - On Hover', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'close_btn_text_color_hover',
							'default' => '#d3ced2',
						),
						array(
							'label' => __( 'Background Color - On Hover', 'addonify-compare-products' ),
							'name' => ADDONIFY_CP_DB_INITIALS . 'close_btn_bck_color_hover',
							'default' => '#f5c40e',
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'table_style',
					'field_label' => __( 'Table Style', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'select' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'table_style',
							'options' => array(
								'default' => __( 'Default', 'addonify-compare-products' ),
								'dark' => __( 'Dark', 'addonify-compare-products' ),
								'light' => __( 'Light', 'addonify-compare-products' ),
								'stripped' => __( 'Stripped', 'addonify-compare-products' ),
							),
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
				array(
					'field_id' => ADDONIFY_CP_DB_INITIALS . 'custom_css',
					'field_label' => __( 'Custom CSS', 'addonify-compare-products' ),
					'field_callback' => array( $this, 'text_area' ),
					'field_callback_args' => array(
						array(
							'name' => ADDONIFY_CP_DB_INITIALS . 'custom_css',
							'attr' => 'rows="5" class="large-text code"',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			),
		);

		// create settings fields.
		$this->create_settings( $settings_args );

	}



	/**
	 * This will create settings section, fields and register that settings in a database from the provided array data
	 *
	 * @since    1.0.0
	 * @param    array $args Data required for this plugin to work.
	 */
	private function create_settings( $args ) {

		// define section ---------------------------.
		add_settings_section( $args['section_id'], $args['section_label'], $args['section_callback'], $args['screen'] );

		foreach ( $args['fields'] as $field ) {

			// create label.
			add_settings_field( $field['field_id'], $field['field_label'], $field['field_callback'], $args['screen'], $args['section_id'], $field['field_callback_args'] );

			foreach ( $field['field_callback_args'] as $sub_field ) {
				register_setting(
					$args['settings_group_name'],
					$sub_field['name'],
					array(
						'sanitize_callback' => $sub_field['sanitize_callback'],
					)
				);
			}
		}
	}


	/**
	 * Show notification after form submission
	 *
	 * @since    1.0.0
	 */
	public function addonify_cp_form_submission_notification_callback() {
		if ( isset( $_GET['page'] ) && $_GET['page'] === $this->settings_page_slug ) {
			settings_errors();
		}
	}


	/**
	 * Show error message if woocommerce is not active
	 *
	 * @since    1.0.0
	 */
	public function addonify_cp_show_woocommerce_not_active_notice_callback() {
		if ( ! $this->is_woocommerce_active() ) {
			add_action(
				'admin_notices',
				function() {
					require dirname( __FILE__ ) . '/templates/woocommerce_not_active_notice.php';
				}
			);
		}
	}


	/**
	 * Create comparision page if it was deleted by user.
	 *
	 * @since    1.0.0
	 * @param    string $string Page to check.
	 */
	public function check_if_comparision_page_exists( $string ) {

		// if display type is "page" but page id is not present or page is deleted by user.
		// create new page and update database.

		$display_type = isset( $_POST[ ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type' ] ) ? sanitize_text_field( wp_unslash( $_POST[ ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type' ] ) ) : '';

		if ( 'page' === $display_type ) {

			$page_id = get_option( ADDONIFY_CP_DB_INITIALS . 'page_id' );

			if ( ! $page_id || 'publish' != get_post_status( $page_id ) ) {

				require_once dirname( __FILE__, 2 ) . '/includes/class-addonify-compare-products-activator.php';

				// generate new page.
				Addonify_Compare_Products_Activator::activate();

			}
		}

		return sanitize_text_field( $string );

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

			require dirname( __FILE__ ) . '/templates/input_textbox.php';
		}
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
	public function overlay_btn_offset_group( $arguments ) {
		require dirname( __FILE__ ) . '/templates/overlay_btn_offset_group.php';
	}


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

			require dirname( __FILE__ ) . '/templates/input_textarea.php';
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

			require dirname( __FILE__ ) . '/templates/input_colorpicker.php';
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
			require dirname( __FILE__ ) . '/templates/checkbox_group.php';
		}
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $args Data required to make this function work.
	 */
	public function checkbox( $args ) {
		$default_state = ( array_key_exists( 'checked', $args ) ) ? $args['checked'] : 1;
		$db_value = get_option( $args['name'], $default_state );
		$is_checked = ( $db_value ) ? 'checked' : '';
		$attr = ( array_key_exists( 'attr', $args ) ) ? $args['attr'] : '';

		require dirname( __FILE__ ) . '/templates/input_checkbox.php';
	}


	/**
	 * Helper function to generate input fields for admin settings page.
	 *
	 * @since    1.0.0
	 * @param    string $arguments Data required to make this function work.
	 */
	public function select( $arguments ) {
		foreach ( $arguments as $args ) {

			$db_value = get_option( $args['name'] );
			$options = ( array_key_exists( 'options', $args ) ) ? $args['options'] : array();

			require dirname( __FILE__ ) . '/templates/input_select.php';
		}
	}


}
