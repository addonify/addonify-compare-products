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

			$this->show_compare_btn = (int) $this->get_db_values('enable_compare_products', 1);

			if( $this->show_compare_btn === 1 ){
				$this->compare_products_btn_position =  $this->get_db_values('compare_products_btn_position', 'after_add_to_cart' );
				$this->compare_products_btn_label = $this->get_db_values( 'compare_products_btn_label' );
			}
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-compare-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// required min version wordpress 4.9.0
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-compare-public.min.js', array( 'jquery' ), $this->version, false );

		// js cookie
		wp_enqueue_script( 'js-cookie', '//cdn.jsdelivr.net/npm/js-cookie@rc/dist/js.cookie.min.js', array('jquery'), $this->version, true );

		// localize ajax script
		wp_localize_script( 
			$this->plugin_name, 
			'addonify_compare_ajax_object', 
			array( 
				'ajax_url' 	=> admin_url( 'admin-ajax.php' ), 
				'action' 	=> 'get_products_thumbnails'
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


	// callback function
	// show compare product btn button before add to cart button
	public function show_compare_products_btn_before_add_to_cart_btn_callback() {

		global $product;
		$product_id = $product->get_id();
		
		// show compare btn before add to cart button
		if( 
			$this->compare_products_btn_position == 'before_add_to_cart' &&
			$this->show_compare_btn && 
			$this->compare_products_btn_label 
		) {
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => '') );
			echo ob_get_clean();

		}

	}


	// callback function
	// show compare product btn after add to cart button
	public function show_compare_products_btn_after_add_to_cart_btn_callback() {

		global $product;
		$product_id = $product->get_id();
		
		// show compare btn after add to cart button
		if( 
			$this->compare_products_btn_position == 'after_add_to_cart' &&
			$this->show_compare_btn && 
			$this->compare_products_btn_label 
		) {
			
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => '') );
			echo ob_get_clean();

		}

	}


	// callback function
	// show quick view button aside image
	public function show_compare_products_btn_aside_image_callback(){

		global $product;
		$product_id = $product->get_id();
		
		if( $this->show_compare_btn && $this->compare_products_btn_position == 'overlay_on_image' ) {
			ob_start();
			$this->get_templates( 'addonify-compare-products-button', false, array( 'product_id' => $product_id, 'label' => $this->compare_products_btn_label, 'css_class' => 'addonify-overlay-image') );
			echo ob_get_clean();
		}

	}


	// callback function
	// add custom markup into footer
	public function add_markup_into_footer_callback(){
		ob_start();
		$this->get_templates( 'addonify-compare-products-bottom-bar' );
		echo ob_get_clean();
	}


	// callback function
	// generate style tag according to options selected by user
	public function generate_custom_styles_callback(){

		return;

		$load_styles_from_plugin = $this->get_db_values( 'load_styles_from_plugin', 1 );

		// do not continue if plugin styles are disabled by user
		if( ! $load_styles_from_plugin ) return;

		$custom_css = $this->get_db_values('custom_css');
		$custom_styles_output = '';

		$style_args = array(
			'.adfy-compare-products-overlay' => array(
				'background' 	=> 'modal_overlay_bck_color'
			),
			'.adfy-compare-products-modal-content' => array(
				'background' 	=> 'modal_bck_color',
			),
			'.adfy-compare-products-modal-content .entry-title' => array(
				'color'		 	=> 'title_color',
			),
			'.star-rating::before' => array(
				'color'		 	=> 'rating_color_empty',
			),
			'.star-rating span::before' => array(
				'color'		 	=> 'rating_color_full',
			),
			'.adfy-compare-products-modal-content .price' => array(
				'color' 		=> 'price_color_regular',
			),
			'.adfy-compare-products-modal-content .price.del' => array(
				'background' 	=> 'price_color_sale',
			),
			'.adfy-compare-products-modal-content .woocommerce-product-details__short-description' => array(
				'color' 		=> 'excerpt_color',
			),
			'.adfy-compare-products-modal-content .product_meta' => array(
				'color' 		=> 'meta_color',
			),
			'.adfy-compare-products-modal-content .product_meta a:hover' => array(
				'color' 		=> 'meta_color_hover',
			),
			'#addonify-qvm-close-button svg' => array(
				'color' 		=> 'close_btn_text_color',
			),
			'#addonify-qvm-close-button' => array(
				'background'	=> 'close_btn_bck_color',
			),
			'#addonify-qvm-close-button:hover svg' => array(
				'color'		 	=> 'close_btn_text_color_hover',
			),
			'#addonify-qvm-close-button:hover' => array(
				'background' 	=> 'close_btn_bck_color_hover',
			),
			'.adfy-compare-products-modal-content button, .adfy-compare-products-modal-content .button' => array(
				'background' 	=> 'other_btn_bck_color',
				'color' 		=> 'other_btn_text_color',
			),
			'.adfy-compare-products-modal-content button:hover, .adfy-compare-products-modal-content .button:hover' => array(
				'background' 	=> 'other_btn_bck_color_hover',
				'color'		 	=> 'other_btn_text_color_hover',
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
	private function generate_contents() {

		return;
		
		// Show Hide Image according to user choices 
		if( (int) $this->get_db_values( 'show_product_image' ) ) {

			// show or hide gallery thumbnails according to user choice
			if( $this->get_db_values( 'product_image_display_type' ) == 'image_only' ) {
				$this->disable_gallery_thumbnails();
			}
			
			// show images
			add_action( 'addonify_cp_product_image', 'woocommerce_show_product_sale_flash', 10 );
			add_action( 'addonify_cp_product_image', 'woocommerce_show_product_images', 20 );
			
		}

		// show or hide title
		if( (int) $this->get_db_values( 'show_product_title' ) ) {
			add_action( 'addonify_cp_product_summary', 'woocommerce_template_single_title', 5 );
		}

		// show or hide product ratings
		if( (int) $this->get_db_values( 'show_product_rating' ) ) {
			add_action( 'addonify_cp_product_summary', 'woocommerce_template_single_rating', 10 );
		}

		// show or hide price
		if( (int) $this->get_db_values( 'show_product_price' ) ) {
			add_action( 'addonify_cp_product_summary', 'woocommerce_template_single_price', 15 );
		}

		// show or hide excerpt
		if( (int) $this->get_db_values( 'show_product_excerpt' ) ) {
			add_action( 'addonify_cp_product_summary', 'woocommerce_template_single_excerpt', 20 );
		}

		// show or hide add to cart button
		if( (int) $this->get_db_values( 'show_add_to_cart_btn' ) ) {
			add_action( 'addonify_cp_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
		}

		// show or hide product meta
		if( (int) $this->get_db_values( 'show_product_meta' ) ) {
			add_action( 'addonify_cp_product_summary', 'woocommerce_template_single_meta', 30 );
		}

		// show  or hide view details button
		if( (int) $this->get_db_values( 'show_view_detail_btn' ) && $this->get_db_values( 'view_detail_btn_label' ) ) {
			add_action( 'addonify_cp_after_product_summary_content', array($this, 'view_details_btn_callback') );
		}

	}


	// modify woocommerce_before_shop_loop_item
	// wrap loop in custom container
	// works only if compare_products_btn_position == overlay_on_image
	public function modify_woocommerce_shop_loop(){
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ){

			// start session
			// if( ! session_id() ) session_start();

			// // check if overlay_container is already created by another addonify plugin
			$is_overlay_added = isset( $_SESSION['is_overlay_added'] ) ? $_SESSION['is_overlay_added'] : 0;

			// var_dump( $is_overlay_added );


			// if( ! $is_overlay_added ){

				// create overlay container

				// $_SESSION['is_overlay_added'] = 1;
				
				add_action ( 'woocommerce_before_shop_loop_item' ,  function (){
					echo '<div class="addonify-qvm-overlay-button">';
				});
					
				add_action ( 'woocommerce_after_shop_loop_item' ,  function (){
					echo '</div>';
				});
				
			// }
		}

	}


	// callback function
	// print opening tag of overlay image container
	public function addonify_overlay_container_start_callback(){
		
		global $overlay_opening_tag_is_added;
		if( $overlay_opening_tag_is_added ) return;

		if( $this->compare_products_btn_position == 'overlay_on_image' ){
			$overlay_opening_tag_is_added = 1;
			echo '<div class="addonify-qvm-overlay-button">';
		}

	}


	// callback function
	// print closing tag of overlay image container
	public function addonify_overlay_container_end_callback(){

		global $overlay_closing_tag_is_added;
		if( $overlay_closing_tag_is_added ) return;
		
		if( $this->compare_products_btn_position == 'overlay_on_image' ){
			$overlay_closing_tag_is_added = 1;
			echo '</div>';
		}

	}



	// require proper templates for use in front end
	private function get_templates( $template_name, $require_once = true, $args = array() ){

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

}
