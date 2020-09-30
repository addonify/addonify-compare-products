<?php

require_once dirname( __FILE__, 2 ) . '/includes/class-addonify-compare-products-helper.php';

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.themebeez.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public
 * @author     Addonify <info@addonify.com>
 */

class Addonify_Compare_Products_Public extends Compare_Products_Helper {

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
	 * State of the plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $enable_plugin    The current state of the plugin
	 */
	private $enable_plugin;

	/**
	 * Compare Button Position
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $compare_products_btn_position    Display Position for the Compare button
	 */
	private $compare_products_btn_position;

	 /**
	 * Compare Button Label
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $compare_products_btn_label    Label for the compare button
	 */
	private $compare_products_btn_label;


	/**
	 * Display type of comparision table ( modal or page )
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $display_type    Display comparision in popup or page
	 */
	private $display_type;


	/**
	 * Page id for showing comparision shortcodes
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $display_type    Display comparision in popup or page
	 */
	 private $compare_page_id;



	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// ajax action is also considered as admin action
		$this->enable_plugin = (int) $this->get_db_values('enable_product_comparision', 1);

		// get default display type of comparisoin table
		$this->display_type = $this->get_db_values( 'compare_products_display_type', 'popup' );

		$this->compare_page_id = get_option( ADDONIFY_CP_DB_INITIALS .'page_id');

		
		if( ! is_admin() ){
			
			// if display type is page but page id doesnot exist ( deleted by user)
			// change display type to popup			
			if( $this->display_type == 'page' ) {
				$this->compare_page_id = get_option( ADDONIFY_CP_DB_INITIALS .'page_id');

				if( ! $this->compare_page_id || 'publish' != get_post_status( $this->compare_page_id ) ) {
					$this->display_type = 'popup';
					update_option( ADDONIFY_CP_DB_INITIALS .'page_id');
				}
			}


			if( $this->enable_plugin === 1 ){
				$this->compare_products_btn_position =  $this->get_db_values('compare_products_btn_position', 'after_add_to_cart' );
				$this->compare_products_btn_label = $this->get_db_values( 'compare_products_btn_label', __( 'Compare', 'addonify-compare-products' ) );
			}

			$this->register_shortcode();
		}
		
	}



	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		// should we load styles ?
		if( ! $this->enable_plugin ) return;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-compare-public.css', array(), time(), 'all' );
	}


	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin ) return;

		// js cookie
		wp_enqueue_script( 'js-cookie', '//cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js', array('jquery'), $this->version, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-compare-public.min.js', array( 'jquery', 'jquery-ui-sortable' ), time(), false );


		$localize_args = array( 
			'ajax_url' 						=> admin_url( 'admin-ajax.php' ), 
			'action_get_thumbnails' 		=> 'get_products_thumbnails',
			'action_search_items' 			=> 'search_items',
			'action_get_compare_contents' 	=> 'get_compare_contents',
			'cookie_expire'				 	=> $this->get_db_values( 'compare_products_cookie_expires', 'browser' ),
			'display_type'				 	=> $this->display_type,
			'comparision_page_url'			=> '',
		);

		if( $this->display_type == 'page' ){
			$localize_args['comparision_page_url'] = get_permalink( $this->get_db_values( 'page_id' ) );
		}

		// localize script
		wp_localize_script( 
			$this->plugin_name, 
			'addonify_compare_ajax_object', 
			$localize_args			
		);

	}


	// tasks that needs to be done during "init"
	public function init_callback(){
		// do something
	}

	
	
	/**
	 * Get thumbnails from product ids.
	 * Accepts only Ajax request
	 *
	 * @since    1.0.0
	 */
	public function get_products_thumbnails_callback(){

		// only ajax request is allowed
		if( ! wp_doing_ajax() ) wp_die( 'Invalid Request' );

		// we are accepting ids as GET parameter rather than cookies
		// because application can also call for single thumbnail

		// product ids is required
		if( ! isset( $_GET['ids'] ) ) wp_die( 'product ids are missing' );

		$product_ids = $_GET['ids'];

		// convert into array
		$product_ids = explode( ',', $product_ids );
		$return_data = array();

		foreach($product_ids as $id){
			$return_data[ $id ] = get_the_post_thumbnail_url( $id, 'thumbnail' );
		}
		
		echo json_encode($return_data);
		wp_die();

	}


	
	/**
	 * Generate contents according to search query provided with ajax requests 
	 * Accepts only Ajax requests
	 *
	 * @since    1.0.0
	 */
	public function search_items_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin ) return;

		// only ajax request is allowed
		if( ! wp_doing_ajax() ) wp_die( 'Invalid Request' );

		// search query is required
		if( ! isset( $_GET['query'] )  ) wp_die( 'search query is required !' );
		
		$query = esc_attr($_GET['query']);
		$items_in_cookie_ar = array();
		$items_in_cookie = $_COOKIE['addonify_selected_product_ids'];

		if( $items_in_cookie ){
			$items_in_cookie_ar = explode(',', $items_in_cookie);
		}

		// skip products that are already in cookies
		$wp_query = new WP_Query( array( 's' => $query, 'post__not_in' =>  $items_in_cookie_ar, 'post_type' => 'product' ) );

		echo '<ul>';

		if( $wp_query->have_posts() ){
			$this->get_templates('addonify-compare-products-search-result', false, array('query' => $wp_query) );
		}
		else{
			echo '<li>No results found for <strong>'. $query .' </strong></li>';
		}
		
		echo '</ul>';
		
		wp_die();

	}


	
	/**
	 * Show compare button after "cart to cart" button
	 *
	 * @since    1.0.0
	 */
	public function show_compare_products_btn_after_add_to_cart_btn_callback(){

		if( $this->compare_products_btn_position == 'after_add_to_cart'  ) {
			$this->show_compare_btn_aside_add_to_cart_btn_callback();
		}
	}

	

	
	/**
	 * Show compare button before "add to cart" button
	 *
	 * @since    1.0.0
	 */
	public function show_compare_products_btn_before_add_to_cart_btn_callback(){
		if( $this->compare_products_btn_position == 'before_add_to_cart'  ) {
			$this->show_compare_btn_aside_add_to_cart_btn_callback();
		}
	}

	
	
	/**
	 * Generating "Compare" button
	 *
	 * @since    1.0.0
	 */
	private function show_compare_btn_aside_add_to_cart_btn_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin ) return;

		global $product;
		$product_id = $product->get_id();

		// show compare btn after add to cart button
		if( $this->compare_products_btn_label ) {

			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => '') );
			echo ob_get_clean();

		}

	}


	
	/**
	 * Show compare button on top of image
	 *
	 * @since    1.0.0
	 */
	public function show_compare_products_btn_aside_image_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin ) return;

		global $product;
		$product_id = $product->get_id();

		if( $this->compare_products_btn_position == 'overlay_on_image' && $this->compare_products_btn_label ) {
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => 'addonify-overlay-btn') );
			echo ob_get_clean();
		}

	}


	
	/**
	 * Generate required markups and print it in footer of the website
	 *
	 * @since    1.0.0
	 */
	public function add_markup_into_footer_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin ) return;

		// do not show following template if it is a shortcode display page
		if( $this->display_type == 'page' && $this->compare_page_id == get_the_ID() ) return;

		ob_start();

		$this->get_templates( 'addonify-compare-products-wrapper', true, array( 'label' => __( 'Compare', 'addonify-compare-products' ) ) );

		if( $this->display_type == 'popup' ){
			$this->get_templates( 'addonify-compare-products-compare-modal-wrapper' );
		}

		echo ob_get_clean();
	}


	
	/**
	 * Generate contents for compare and print it
	 * Can be used in ajax requests or in shortcodes
	 *
	 * @since    1.0.0
	 * @param      string    $product_ids       Get product ids from cookies
	 */
	public function get_compare_contents_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin ) return;

		// get product ids from cookies
		$product_ids = isset( $_COOKIE['addonify_selected_product_ids'] ) ? $_COOKIE['addonify_selected_product_ids'] : '';

		// convert into array
		$product_ids = explode( ',', $product_ids );

		// generate contents and return as array data
		$compare_data = $this->generate_contents_data( $product_ids );

		// echo '<pre>';
		// var_dump( $title_data );
		// die;


		// $title_data = apply_filters( 'addonify_compare_products_table_title', );

		ob_start();

		$this->get_templates( 'addonify-compare-products-content', true, array( 'data' => $compare_data ) );
		
		if( wp_doing_ajax() ) {
			echo ob_get_clean();
			wp_die();
		}
		else{
			return ob_get_clean();
		}

	}


	/**
	 * Prepare data to be used in comparision table
	 *
	 * @since    1.0.0
	 * @param      string    $selected_product_ids       Product ids
	 */
	private function generate_contents_data( $selected_product_ids ) {

		$selected_products_data = array();

		if ( is_array( $selected_product_ids ) && ( count( $selected_product_ids ) > 0 ) ) {

			foreach ( $selected_product_ids as $product_id ) {

				$product = wc_get_product( $product_id );
				$parent_product = false;

				if ( ! $product )  continue;

				if ( $product->is_type( 'variation' ) ) {
					$parent_product = wc_get_product( $product->get_parent_id() );
				}


				if( (int) $this->get_db_values( 'show_product_title', 1 ) ) {
					$selected_products_data['a_title'][] = '<a href="' . $product->get_permalink() . '" >' . wp_strip_all_tags( $product->get_formatted_name() ) . '</a>';
				}


				if( (int) $this->get_db_values( 'show_product_image', 1 ) ) {
					$selected_products_data['b_Image'][] =  '<a href="' . $product->get_permalink() . '" >' . $product->get_image( get_option( '_wooscp_image_size', 'wooscp-large' ), array( 'draggable' => 'false' ) ) . '</a>';
				}


				if( (int) $this->get_db_values( 'show_product_price', 1 ) ) {
					$selected_products_data['c_Price'][] =  $product->get_price_html();
				}

				if( (int) $this->get_db_values( 'show_product_excerpt', 1 ) ) {
					if ( $product->is_type( 'variation' ) ) {
						$selected_products_data['d_Description'][] =  $product->get_description();
					} else {
						$selected_products_data['d_Description'][] =  $product->get_short_description();
					}
				}

				if( (int) $this->get_db_values( 'show_product_rating', 1 ) ) {
					$selected_products_data['e_Rating'][] =  wc_get_rating_html( $product->get_average_rating() );
				}


				if( (int) $this->get_db_values( 'show_stock_info', 1 ) ) {
					$selected_products_data['f_Stock'][] =  wc_get_stock_html( $product );
				}			


				if( (int) $this->get_db_values( 'show_attributes', 1 ) ) {

					// ob_start();
					// wc_display_product_attributes( $product );
					// $additional = ob_get_clean();

					// $selected_products_data['Attributes'][] =  $additional;

					foreach( $this->cw_woo_attribute( $product ) as $attribute_key => $attribute_value ){
						$selected_products_data[ 'g_' .$attribute_key ][] =  $attribute_value;
					}

				}


				if( (int) $this->get_db_values( 'show_add_to_cart_btn', 1 ) ) {
					$selected_products_data['h_Add to Cart'][] =   do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style="" ]' );
				}

			}

		}

		ksort( $selected_products_data );

		$selected_products_data_new = array();

		foreach($selected_products_data as $key => $value){
			$selected_products_data_new[ substr( $key, 2 ) ] = $value;
		}


		$selected_products_data = $selected_products_data_new;

		// echo '<pre>';
		// var_dump( $selected_products_data );
		// die;

		// create atleast 3 <td> in frontend table if display type is popup
		if( $this->display_type == 'popup' ){
			foreach( $selected_products_data as $key => $value){

				if( $key == 'Image' ){
					$key_placeholder = 'placeholder-image';
				}
				else{
					$key_placeholder = 'placeholder-default';
				}

				if( count( $selected_products_data[$key] ) === 1){
					$selected_products_data[$key][$key_placeholder . ' ' . $key_placeholder .'-1'] = '';
					$selected_products_data[$key][$key_placeholder . ' ' . $key_placeholder .'-2'] = '';
				}

				if( count( $selected_products_data[$key] ) === 2){
					$selected_products_data[$key][$key_placeholder] = '';
				}
				
			}
		}

		return $selected_products_data;

	}



	private function cw_woo_attribute( $product ){

		$attributes = $product->get_attributes();

		// echo '<pre>';
		// var_dump( $attributes );
		// echo '</pre>';
		// die;

		$display_result = array();
	
		foreach ( $attributes as $attribute ) {
	
			$name = $attribute->get_name();
			if ( $attribute->is_taxonomy() ) {
	
				$terms = wp_get_post_terms( $product->get_id(), $name, 'all' );
	
				$cwtax = $terms[0]->taxonomy;
	
				$cw_object_taxonomy = get_taxonomy($cwtax);
	
				if ( isset ($cw_object_taxonomy->labels->singular_name) ) {
					$tax_label = $cw_object_taxonomy->labels->singular_name;
				} elseif ( isset( $cw_object_taxonomy->label ) ) {
					$tax_label = $cw_object_taxonomy->label;
					if ( 0 === strpos( $tax_label, 'Product ' ) ) {
						$tax_label = substr( $tax_label, 8 );
					}
				}
				
				$tax_terms = array();
				foreach ( $terms as $term ) {
					$single_term = esc_html( $term->name );
					array_push( $tax_terms, $single_term );
				}

				$display_result[ $tax_label ] = implode(', ', $tax_terms);
	
			} else {
				$display_result[ $name ] = esc_html( implode( ', ', $attribute->get_options() ) );
			}
		}

		// echo '<pre>';
		// var_dump( $display_result );
		// echo '</pre>';
		// die;


		return ( ! empty( $display_result ) )  ? $display_result : array() ;
	}


	
	
	/**
	 * Generate custom style tag and print it in header of the website
	 *
	 * @since    1.0.0
	 */
	public function generate_custom_styles_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		// do not continue if plugin styles are disabled by user
		if( ! $this->enable_plugin || ! $this->get_db_values( 'load_styles_from_plugin' ) ) return;


		// add table styles into body class
		add_filter( 'body_class', function( $classes ) {
			return array_merge( $classes, array( 'addonify-compare-table-style-' . $this->get_db_values('table_style') ) );
		} );


		$custom_css = $this->get_db_values('custom_css');

		$style_args = array(
			'button.addonify-cp-button' => array(
				'background' 	=> 'compare_btn_bck_color',
				'color' 		=> 'compare_btn_text_color',
				'left' 			=> 'compare_products_btn_left_offset',
				'right' 		=> 'compare_products_btn_right_offset',
				'top' 			=> 'compare_products_btn_top_offset',
				'bottom'		=> 'compare_products_btn_bottom_offset'
			),
			'#addonify-compare-modal, #addonify-compare-search-modal' => array(
				'background' 	=> 'modal_overlay_bck_color'
			),
			'.addonify-compare-model-inner, .addonify-compare-search-model-inner' => array(
				'background' 	=> 'modal_bck_color',
			),
			'#addonofy-compare-products-table th a' => array(
				'color'		 	=> 'table_title_color',
			),
			'.addonify-compare-all-close-btn svg' => array(
				'color' 		=> 'close_btn_text_color',
			),
			'.addonify-compare-all-close-btn' => array(
				'background'	=> 'close_btn_bck_color',
			),
			'.addonify-compare-all-close-btn:hover svg' => array(
				'color'		 	=> 'close_btn_text_color_hover',
			),
			'.addonify-compare-all-close-btn:hover' => array(
				'background' 	=> 'close_btn_bck_color_hover',
			),
			
		);

		$custom_styles_output = $this->generate_styles_markups( $style_args );

		// avoid empty style tags
		if( $custom_styles_output || $custom_css ){
			echo "<style id=\"addonify-compare-products-styles\"  media=\"screen\"> \n" . $custom_styles_output .  $custom_css . "\n </style>\n";
		}

	}


	/**
	 * Generate style markups
	 *
	 * @since    1.0.0
	 * @param    $style_args    Style args to be processed
	 */
	private function generate_styles_markups( $style_args ){
		$custom_styles_output = '';
		foreach($style_args as $css_sel => $property_value){

			$properties = '';

			foreach( $property_value as $property => $db_field){

				$css_unit = '';

				if( is_array($db_field) ){
					$db_value = $this->get_db_values( $db_field[0] );
					$css_unit = $db_field[1];
				}
				else{
					$db_value = $this->get_db_values( $db_field );
				}
					
				if( $db_value ){
					$properties .=  $property . ': ' . $db_value . $css_unit . '; ';
				}

			}
			
			if( $properties ){
				$custom_styles_output .= $css_sel . '{' . $properties . '}';
			}

		}

		return $custom_styles_output;
	}


	
	/**
	 * Print opening tag of overlay container
	 *
	 * @since    1.0.0
	 */
	public function addonify_overlay_container_start_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin) return;

		// do not continue if overlay is already added by another addonify plugin
		if( defined('ADDONIFY_OVERLAY_IS_ADDED') && ADDONIFY_OVERLAY_ADDED_BY != 'compare_products' ) return;
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ){

			if( ! defined('ADDONIFY_OVERLAY_IS_ADDED')) {
				define('ADDONIFY_OVERLAY_IS_ADDED', 1);
			}

			if( ! defined('ADDONIFY_OVERLAY_ADDED_BY')) {
				define('ADDONIFY_OVERLAY_ADDED_BY', 'compare_products' );
			}

			echo '<div class="addonify-overlay-buttons">';
		}

	}



	/**
	 * Print closing tag of the overlay container
	 *
	 * @since    1.0.0
	 */
	public function addonify_overlay_container_end_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->enable_plugin  ) return;

		// do not continue of overlay is alrady added by another addonify plugin
		if( defined('ADDONIFY_OVERLAY_IS_ADDED') && ADDONIFY_OVERLAY_ADDED_BY != 'compare_products' ) return;
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ){
			echo '</div>';
		}

	}



	/**
	 * Get Database values for selected fields
	 *
	 * @since    1.0.0
	 * @param    $field_name    Database Option Name
	 * @param    $default		Default Value
	 */
	private function get_db_values($field_name, $default = NULL ){
		return get_option( ADDONIFY_CP_DB_INITIALS . $field_name, $default );
	}


	
	/**
	 * Require proper templates for use in front end
	 *
	 * @since    1.0.0
	 * @param    $template_name		Name of the template
	 * @param    $require_once		Should use require_once or require ?
	 * @param    $data				Data to pass to template
	 */
	private function get_templates( $template_name, $require_once = true, $data = array() ){

		// first look for template in themes/addonify/templates
		$theme_path = get_template_directory() . '/addonify/addonify-compare-products/' . $template_name .'.php';
		$plugin_path = dirname( __FILE__ ) .'/templates/' . $template_name .'.php';
		$template_path = file_exists( $theme_path ) ? $theme_path : $plugin_path;

		extract($data);

		if( $require_once ){
			require_once $template_path;
		}
		else{
			require $template_path;
		}
	
	}

	

	/**
	 * Register shortcode to use in comparision page
	 *
	 * @since    1.0.0
	 * @param    $template_name		Name of the template
	 * @param    $require_once		Should use require_once or require ?
	 * @param    $data				Data to pass to template
	 */
	private function register_shortcode(){
		add_shortcode( 'addonify-compare-products', array( $this, 'get_compare_contents_callback' ) );
	}
	

}
