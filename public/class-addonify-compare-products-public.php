<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.themebeez.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public
 * @author     Addonify <info@addonify.com>
 */
class Addonify_Compare_Products_Public {

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
	 * Enable Compare Product Plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $show_compare_btn;

	/**
	 * Compare Button Position
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $compare_products_btn_position;

	 /**
	 * Compare Button Label
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $compare_products_btn_label;


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

		if( ! is_admin() ){

			$this->show_compare_btn = (int) $this->get_db_values('enable_product_comparision', 1);

			if( $this->show_compare_btn === 1 ){
				$this->compare_products_btn_position =  $this->get_db_values('compare_products_btn_position', 'after_add_to_cart' );
				$this->compare_products_btn_label = $this->get_db_values( 'compare_products_btn_label', __('Compare', 'addonify-compare-products') );
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
		
		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-compare-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;

		// required min version wordpress 4.9.0
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		// js cookie
		wp_enqueue_script( 'js-cookie', '//cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js', array('jquery'), $this->version, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-compare-public.min.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, false );


		// localize ajax script
		wp_localize_script( 
			$this->plugin_name, 
			'addonify_compare_ajax_object', 
			array( 
				'ajax_url' 						=> admin_url( 'admin-ajax.php' ), 
				'action_get_thumbnails' 		=> 'get_products_thumbnails',
				'action_search_items' 			=> 'search_items',
				'action_get_compare_contents' 	=> 'get_compare_contents',
				'cookie_expire'				 	=> $this->get_db_values( 'compare_products_cookie_expires', 'browser' ),
				'display_type'				 	=> $this->get_db_values( 'compare_products_display_type', 'popup' ),
				'comparision_page_url'			=> get_permalink( $this->get_db_values( 'page_id' ) )
			) 
		);

	}

	
	
	// ajax request
	// callback function
	// get contents for quick view
	public function get_products_thumbnails_callback(){

		// only ajax request is allowed
		if( ! wp_doing_ajax() ) wp_die( 'Invalid Request' );

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


	// ajax request
	// callback function
	// search for products
	public function search_items_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;


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


	// ajax request
	// callback function
	// get contents to compare
	public function get_compare_contents_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;

		// get product ids from cookies
		$product_ids = isset( $_COOKIE['addonify_selected_product_ids'] ) ? $_COOKIE['addonify_selected_product_ids'] : '';

		// convert into array
		$product_ids = explode( ',', $product_ids );

		// generate contents
		$compare_data = $this->generate_contents_data( $product_ids );

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


	// callback function
	// show compare product btn button before add to cart button
	public function show_compare_products_btn_before_add_to_cart_btn_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;

		global $product;
		$product_id = $product->get_id();
		
		// show compare btn before add to cart button
		if( $this->compare_products_btn_position == 'before_add_to_cart' && $this->compare_products_btn_label ) {
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => '') );
			echo ob_get_clean();

		}

	}


	// callback function
	// show compare product btn after add to cart button
	public function show_compare_products_btn_after_add_to_cart_btn_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;

		global $product;
		$product_id = $product->get_id();
		
		// show compare btn after add to cart button
		if( $this->compare_products_btn_position == 'after_add_to_cart' && $this->compare_products_btn_label ) {
			
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => '') );
			echo ob_get_clean();

		}

	}


	// callback function
	// show quick view button aside image
	public function show_compare_products_btn_aside_image_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;


		global $product;
		$product_id = $product->get_id();
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ) {
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => 'addonify-overlay-image') );
			echo ob_get_clean();
		}

	}


	// callback function
	// add custom markup into footer
	public function add_markup_into_footer_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;

		ob_start();
		$this->get_templates( 'addonify-compare-products-wrapper', true, array( 'label' => $this->compare_products_btn_label  ) );

		if( $this->get_db_values('display_type') == 'popup' ){
			$this->get_templates( 'addonify-compare-products-compare-modal-wrapper' );
		}
		echo ob_get_clean();
	}


	// callback function
	// generate style tag according to options selected by user
	public function generate_custom_styles_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;


		$load_styles_from_plugin = $this->get_db_values( 'load_styles_from_plugin', 0 );

		// do not continue if plugin styles are disabled by user
		if( ! $load_styles_from_plugin ) return;


		// add table styles into body class
		add_filter( 'body_class', function( $classes ) {
			return array_merge( $classes, array( 'addonify-compare-table-style-' . $this->get_db_values('table_style') ) );
		} );



		// generate style markups

		$custom_css = $this->get_db_values('custom_css');
		$custom_styles_output = '';

		$style_args = array(
			'button.addonify-cp-button' => array(
				'background' 	=> 'compare_btn_bck_color',
				'color' 		=> 'compare_btn_text_color'
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

		foreach($style_args as $css_sel => $property_value){

			$properties = '';

			foreach( $property_value as $property => $db_field){
				$db_value = $this->get_db_values( $db_field );

				if( $db_value ){
					$properties .=  $property . ': ' . $db_value .'; ';
				}
			}
			
			if( $properties ){
				$custom_styles_output .= $css_sel . '{' . $properties .'}';
			}

		}

		// avoid empty style tags
		if( $custom_styles_output || $custom_css ){
			echo "<style id=\"addonify-compare-products-styles\"  media=\"screen\"> \n" . $custom_styles_output .  $custom_css . "\n </style>\n";
		}

	}


	// helper function
	// get db values for selected fields
	private function get_db_values($field_name, $default = NULL ){
		return get_option( ADDONIFY_CP_DB_INITIALS . $field_name, $default );
	}


	// generate contents dynamically to modal templates with hooks
	// called by get_compare_products_contents()
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

				if ( intval( $this->get_db_values( 'show_product_title' ) ) ) {

					$selected_products_data['a_title'][] = '<a href="' . $product->get_permalink() . '" >' . wp_strip_all_tags( $product->get_title() ) . '</a>' . $delete_btn;
				}

				if( (int) $this->get_db_values( 'show_product_title', 1 ) ) {
					$selected_products_data['title'][] = '<a href="' . $product->get_permalink() . '" >' . wp_strip_all_tags( $product->get_formatted_name() ) . '</a>';
				}

				if ( intval( $this->get_db_values( 'show_product_price' ) ) ) {
					$selected_products_data['c_Price'][] = '<span class="price">' . $product->get_price_html() . '</span>';
				}

				if( (int) $this->get_db_values( 'show_product_image', 1 ) ) {
					$selected_products_data['Image'][] =  '<a href="' . $product->get_permalink() . '" >' . $product->get_image( get_option( '_wooscp_image_size', 'wooscp-large' ), array( 'draggable' => 'false' ) ) . '</a>';
				}


				if( (int) $this->get_db_values( 'show_product_price', 1 ) ) {
					$selected_products_data['Price'][] =  $product->get_price_html();
				}

				if( (int) $this->get_db_values( 'show_product_excerpt', 1 ) ) {
					if ( $product->is_type( 'variation' ) ) {
						$selected_products_data['Description'][] =  $product->get_description();
					} else {
						$selected_products_data['Description'][] =  $product->get_short_description();
					}
				}

				if( (int) $this->get_db_values( 'show_product_rating', 1 ) ) {
					$selected_products_data['Rating'][] =  wc_get_rating_html( $product->get_average_rating() );
				}


				if( (int) $this->get_db_values( 'show_stock_info', 1 ) ) {
					$selected_products_data['Stock'][] =  wc_get_stock_html( $product );
				}			


				if( (int) $this->get_db_values( 'show_attributes', 1 ) ) {

					ob_start();
						wc_display_product_attributes( $product );
						$additional = ob_get_contents();
					ob_end_clean();

					$selected_products_data['Attributes'][] =  $additional;

				}


				if( (int) $this->get_db_values( 'show_add_to_cart_btn', 1 ) ) {
					$selected_products_data['Add To Cart'][] =   do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style="" ]' );
				}

			}

		}

		// craete atleast 3 <td> in frontend table
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

		return $selected_products_data;

	}


	// callback function
	// print opening tag of overlay image container
	public function addonify_overlay_container_start_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ){
			$overlay_opening_tag_is_added = 1;
			echo '<div class="addonify-compare-overlay-button">';
		}

	}


	// callback function
	// print closing tag of overlay image container
	public function addonify_overlay_container_end_callback(){

		// do not continue if "Enable Product Comparision" is not checked
		if( ! $this->show_compare_btn ) return;
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ){
			$overlay_closing_tag_is_added = 1;
			echo '</div>';
		}

	}


	// require proper templates for use in front end
	private function get_templates( $template_name, $require_once = true, $data = array() ){

		// first look for template in themes/addonify/templates
		$theme_path = get_template_directory() . '/addonify/' . $template_name .'.php';
		$plugin_path = dirname( __FILE__ ) .'/templates/' . $template_name .'.php';

		if( file_exists( $theme_path ) ){
			$template_path = $theme_path;
		}
		else{
			$template_path = $plugin_path;
		}

		if( $require_once ){
			require_once $template_path;
		}
		else{
			require $template_path;
		}
	
	}


	private function register_shortcode(){
		add_shortcode( 'addonify-compare-products', array( $this, 'get_compare_contents_callback' ) );
	}

	

}
