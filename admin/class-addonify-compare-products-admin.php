<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.themebeez.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Compare_Products_Admin {

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
	 * Default settings_page_slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	 private $settings_page_slug = 'addonify_compare_products';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// load styles in plugin page only
		if( isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug ){

			// toggle switch
			wp_enqueue_style( 'lc_switch', plugin_dir_url( __FILE__ ) . 'css/lc_switch.css' );

			
			if( version_compare( get_bloginfo('version'),'3.5', '>=' ) ){
				// features available from wordpress 3.5

				// built in wp color picker
				wp_enqueue_style( 'wp-color-picker' );
			}

			// admin css
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/addonify-compare-products-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// load scripts in plugin plage only
		if( isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug ){

			$code_editor_is_available = 0;
			$color_picker_is_available = 0;


			if( version_compare( get_bloginfo('version'),'3.5', '>=' ) ){
				$color_picker_is_available = 1;
			}


			if( version_compare( get_bloginfo('version'),'4.9', '>=' ) ){
				$code_editor_is_available = 1;
				
				// features available from wordpress 4.9.0
				wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

			}
			
			// toggle switch
			wp_enqueue_script( 'lc_switch', plugin_dir_url( __FILE__ ) . 'js/lc_switch.min.js', array( 'jquery' ), '', false );

			wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ) );

			// use wp-color-picker-alpha as dependency
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/addonify-compare-products-admin.js', array( 'jquery', 'wp-color-picker-alpha' ), $this->version, false );


			wp_localize_script( 
				$this->plugin_name, 
				'addonify_objects', 
				array( 
					'code_editor_is_available' 		=> $code_editor_is_available,
					'color_picker_is_available' 	=> $color_picker_is_available
				)
			);

		}

	}

	// check if woocommerce is active
	private function is_woocommerce_active() {
		if ( class_exists( 'woocommerce' ) )  return true; 
		return false;
	}



	// callback function
	// admin menu
	public function add_menu_callback(){

		// do not show menu if woocommerce is not active
		if ( ! $this->is_woocommerce_active() )  return; 

		global $menu;
		$addonify_menu_exists = false;
		
		foreach($menu as $item) {
			if(strtolower($item[0]) == strtolower('Addonify')) {
				$addonify_menu_exists = true;
				break;
			}
		}


		if( ! $addonify_menu_exists ){
			add_menu_page( 'Addonify Settings', 'Addonify', 'manage_options', $this->settings_page_slug, array($this, 'get_settings_screen_contents'), plugin_dir_url( __FILE__ ) .'/templates/addonify-logo.svg', 76 );
		}
		

		// sub menu
		// redirects to main plugin link
		add_submenu_page(  'addonify_quick_view', 'Addonify Compare Products Settings', 'Compare Products', 'manage_options', $this->settings_page_slug, array($this, 'get_settings_screen_contents'), 1 );

	}



	// callback function
	// add custom "settings" link in the plugins.php page
	public function custom_plugin_link_callback( $links, $file ){
		
		if ( $file == plugin_basename(dirname(__FILE__, 2) . '/addonify-compare-products.php') ) {
			// add "Settings" link
			$links[] = '<a href="admin.php?page='. $this->settings_page_slug .'">' . __('Settings','addonify-compare-products') . '</a>';
		}
		
		return $links;
	}



	// callback function
	// get contents for settings page screen
	public function get_settings_screen_contents(){
		$current_tab = ( isset($_GET['tabs']) ) ? $_GET['tabs'] : 'settings';
		$tab_url = "admin.php?page=$this->settings_page_slug&tabs=";

		ob_start();
		require_once dirname( __FILE__ ) .'/templates/settings-screen.php';
		echo ob_get_clean();

	}


	// callback function
	// generate settings page form elements
	public function settings_page_ui() {

		// ---------------------------------------------
		// General Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'compare_products_settings',
			'section_id' 			=> 'general_options',
			'section_label'			=> __('GENERAL OPTIONS', 'addonify-compare-products' ),
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-settings',
			'fields'				=> array(
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'enable_product_comparision',
					'field_label'			=> __('Enable Product Comparision', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "toggle_switch"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> ADDONIFY_CP_DB_INITIALS . 'enable_product_comparision', 
							'checked' 			=> 1,
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					) 
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_position',
					'field_label'			=> __('Compare Button Position', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "select"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_position', 
							'options' 			=> array(
								'before_add_to_cart' 	=> __('Before Add To Cart Button', 'addonify-compare-products' ),
								'after_add_to_cart'		=> __('After Add To Cart Button', 'addonify-compare-products' ),
								'overlay_on_image'		=> __('Overlay On The Product Image', 'addonify-compare-products' )
							),
							'sanitize_callback'			=> 'sanitize_text_field'
						),
					) 
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_label',
					'field_label'			=> __('Compare Product Button Label', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array( 
						array(
							'name'			 	=> ADDONIFY_CP_DB_INITIALS . 'compare_products_btn_label', 
							'default'		 	=> __('Compare', 'addonify-compare-products'),
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					), 
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type',
					'field_label'			=> __('Display Comparision in', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "select"),
					'field_callback_args'	=> array( 
						array(
							'name'			 	=> ADDONIFY_CP_DB_INITIALS . 'compare_products_display_type', 
							'options' 			=> array(
								'popup' 		=> __('Popup Modal', 'addonify-compare-products' ),
								'page'			=> __('Comparision Page', 'addonify-compare-products' ),
							),
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					), 
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'compare_products_comparision_page',
					'field_label'			=> __('Comparision Page', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "get_all_pages_to_select_field"),
					'field_callback_args'	=> array( 
						array(
							'name'			 	=> ADDONIFY_CP_DB_INITIALS . 'compare_products_comparision_page', 
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					), 
				),
				
			)
		);

		// create settings fields
		$this->create_settings( $settings_args );


		// ---------------------------------------------
		// Contents Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'compare_products_settings',
			'section_id' 			=> 'table_options',
			'section_label'			=> __('TABLE OPTIONS', 'addonify-compare-products' ),
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-settings-table-options',
			'fields'				=> array(
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'table_title',
					'field_label'			=> __('Table Title', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "text_box"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> ADDONIFY_CP_DB_INITIALS . 'table_title', 
							'default'			=> __('Compare Products', 'addonify-compare-products' ),
							'sanitize_callback'	=> 'sanitize_text_field'
						),
					),
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'fields_to_compare',
					'field_label'			=> __('Fields To Compare', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "checkbox_with_label"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Product Image', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_product_image',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Product Title', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_product_title',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Product Rating', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_product_rating',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Product Price', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_product_price',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Product Excerpt', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_product_excerpt',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Stock Info', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_stock_info',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Attributes', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_attributes',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Add To Cart Button', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'show_add_to_cart_btn',
							'type'				=> 'number',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
					),
				),
				
			),
		);

		// create settings fields
		$this->create_settings( $settings_args );


		// ---------------------------------------------
		// Styles Options
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'compare_products_styles',
			'section_id' 			=> 'style_options',
			'section_label'			=> __('STYLE OPTIONS', 'addonify-compare-products' ),
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-styles',
			'fields'				=> array(
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'load_styles_from_plugin',
					'field_label'			=> __('Load Styles From Plugin', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "toggle_switch"),
					'field_callback_args'	=> array( 
						array(
							'name' 				=> ADDONIFY_CP_DB_INITIALS . 'load_styles_from_plugin', 
							'checked' 			=> 1,
							'sanitize_callback'	=> 'sanitize_textarea_field'
						)
					) 
				),
			)
		);

		// create settings fields
		$this->create_settings( $settings_args );


		// ---------------------------------------------
		// Content Colors
		// ---------------------------------------------

		$settings_args = array(
			'settings_group_name'	=> 'compare_products_styles',
			'section_id' 			=> 'content_colors',
			'section_label'			=> __('CONTENT COLORS', 'addonify-compare-products' ),
			'section_callback'		=> '',
			'screen'				=> $this->settings_page_slug.'-content-colors',
			'fields'				=> array(
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'modal_box_color',
					'field_label'			=> __('Modal Box', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Overlay Background Color', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'modal_overlay_bck_color',
							'default'			=> '#000000',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Background Color', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'modal_bck_color',
							'default'			=> '#ffffff',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						
					),
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'table_title_color',
					'field_label'			=> __('Table Title', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'table_title_color',
							'default'			=> '#000000',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						
					),
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'close_btn_color',
					'field_label'			=> __('Close Button', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "color_picker_group"),
					'field_callback_args'	=> array( 
						array(
							'label'				=> __('Text Color', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'close_btn_text_color',
							'default'			=> '#d3ced2',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Background Color', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'close_btn_bck_color',
							'default'			=> '#f5c40e',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Text Color - On Hover', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'close_btn_text_color_hover',
							'default'			=> '#d3ced2',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						array(
							'label'				=> __('Background Color - On Hover', 'addonify-compare-products'),
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'close_btn_bck_color_hover',
							'default'			=> '#f5c40e',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						
					),
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'table_style',
					'field_label'			=> __('Table Style', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "select"),
					'field_callback_args'	=> array( 
						array(
							'name'			 	=> ADDONIFY_CP_DB_INITIALS . 'table_style', 
							'options' 			=> array(
								'default' 		=> __('Default', 'addonify-compare-products' ),
								'dark'			=> __('Dark', 'addonify-compare-products' ),
								'light'			=> __('Light', 'addonify-compare-products' ),
								'stripped'		=> __('Stripped', 'addonify-compare-products' ),
							),
							'sanitize_callback'	=> 'sanitize_text_field'
						)
					),
				),
				array(
					'field_id'				=> ADDONIFY_CP_DB_INITIALS . 'custom_css',
					'field_label'			=> __('Custom CSS', 'addonify-compare-products' ),
					'field_callback'		=> array($this, "text_area"),
					'field_callback_args'	=> array( 
						array(
							'name'				=> ADDONIFY_CP_DB_INITIALS . 'custom_css',
							'attr'				=> 'rows="5" class="large-text code"',
							'sanitize_callback'	=> 'sanitize_text_field'
						),
						
					),
				),
				
			)
		);

		// create settings fields
		$this->create_settings( $settings_args );
		
	}


	// this function will create settings section, fields and register that settings in a database
	private function create_settings($args){
		
		// define section ---------------------------
		add_settings_section($args['section_id'], $args['section_label'], $args['section_callback'], $args['screen'] );

		foreach($args['fields'] as $field){
			
			// create label
			add_settings_field( $field['field_id'], $field['field_label'], $field['field_callback'], $args['screen'], $args['section_id'], $field['field_callback_args'] );
			
			foreach( $field['field_callback_args'] as $sub_field){
				register_setting( $args['settings_group_name'],  $sub_field['name'], array(
        			'sanitize_callback' => $sub_field['sanitize_callback']
				));
			}

		}
		
	}


	// -------------------------------------------------
	// form element helpers 
	// -------------------------------------------------

	public function text_box($arguments){
		foreach($arguments as $args){
			$default = isset( $args['default'] ) ? $args['default'] : '';
			$db_value = get_option($args['name'], $default);

			ob_start();
			require dirname( __FILE__ ) .'/templates/input_textbox.php';
			echo ob_get_clean();
		}
	}

	public function text_area($arguments){
		foreach($arguments as $args){
			$placeholder = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
			$db_value = get_option($args['name'], $placeholder);
			$attr = isset( $args['attr'] ) ? $args['attr'] : '';

			ob_start();
			require dirname( __FILE__ ) .'/templates/input_textarea.php';
			echo ob_get_clean();
		}
	}

	public function toggle_switch($arguments){
		foreach($arguments as $args){
			$args['attr'] = ' class="lc_switch"';
			$this->checkbox($args);
		}
	}

	public function color_picker_group($args){
		foreach($args as $arg){
			$default =  isset( $arg['default'] )  ? $arg['default'] : '';
			$db_value = ( get_option( $arg['name'] )) ? get_option( $arg['name'] ) : $default;

			ob_start();
			require dirname( __FILE__ ) .'/templates/input_colorpicker.php';
			echo ob_get_clean();
		}
	}

	public function checkbox_with_label($args){
		foreach($args as $arg){
			ob_start();
			require dirname( __FILE__ ) .'/templates/checkbox_group.php';
			echo ob_get_clean();
		}
	}

	public function checkbox($args){
		$default_state = ( array_key_exists('checked', $args) ) ? $args['checked'] : 1;
		$db_value = get_option($args['name'], $default_state);
		$is_checked = ( $db_value ) ? 'checked' : '';
		$attr = ( array_key_exists('attr', $args) ) ? $args['attr'] : '';

		ob_start();
		require dirname( __FILE__ ) .'/templates/input_checkbox.php';
		echo ob_get_clean();
	}

	public function select($arguments){
		foreach($arguments as $args){

			$db_value = get_option($args['name']);
			$options = ( array_key_exists('options', $args) ) ? $args['options'] : array();
			
			ob_start();
			require dirname( __FILE__ ) .'/templates/input_select.php';
			echo ob_get_clean();
		}
	}

	public function get_all_pages_to_select_field($arguments){

		$pages_ar = array();
		$pages = get_pages(); 

		foreach ( $pages as $page ) {
			$pages_ar[ $page->ID ] = $page->post_title;
		}

		$arguments[0]['options'] = $pages_ar;

		$this->select($arguments);
	}



	// callback function
	// show notification after form submission
	public function addonify_cp_form_submission_notification_callback(){
		if( isset($_GET['page']) && $_GET['page'] == $this->settings_page_slug ){
			settings_errors();			
		}
	}



	// callback
	// show error message in dashboard if woocommerce is not active
	public function addonify_cp_show_woocommerce_not_active_notice_callback(){

		if( ! $this->is_woocommerce_active() ){
			add_action('admin_notices', 'addonify_cp_woocommerce_not_active_notice' );
		}


		function addonify_cp_woocommerce_not_active_notice() {
			ob_start();
			require dirname( __FILE__ ) .'/templates/woocommerce_not_active_notice.php';
			echo ob_get_clean();
		}

	}


}
