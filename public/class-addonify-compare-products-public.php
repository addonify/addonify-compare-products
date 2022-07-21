<?php
/**
 * The Public side of the plugin
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin
 */

/**
 * The Public side of the plugin
 *
 * Defines the plugin name, version, and other required variables.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin
 * @author     Addodnify <info@addonify.com>
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
	 * The compare cookie data.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $compare_cookie_items    The compare cookie data.
	 */
	private $compare_cookie_items;

	/**
	 * Number of items in compare cookie.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $compare_cookie_items_count    Number of items in compare cookie.
	 */
	private $compare_cookie_items_count;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->compare_cookie_items = $this->get_compare_cookie_items();

		$this->compare_cookie_items_count = $this->get_compare_cookie_items_count();
	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'perfect-scrollbar', plugin_dir_url( __FILE__ ) . 'assets/build/css/conditional/perfect-scrollbar.css', array(), $this->version );

		if ( is_rtl() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/public-rtl.css', array(), $this->version );
		} else {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/public.css', array(), $this->version );
		}

		if ( (int) addonify_compare_products_get_option( 'load_styles_from_plugin' ) === 1 ) {

			$inline_css = $this->dynamic_css();

			$custom_css = addonify_compare_products_get_option( 'custom_css' );

			if ( $custom_css ) {
				$inline_css .= $custom_css;
			}
			
			$inline_css = $this->minify_css( $inline_css );

			wp_add_inline_style( $this->plugin_name, $inline_css );
		}
	}


	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'perfect-scrollbar', plugin_dir_url( __FILE__ ) . 'assets/build/js/conditional/perfect-scrollbar.min.js', null, $this->version, true );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/public.min.js', array( 'jquery' ), $this->version, true );

		$localize_args = array(
			'ajaxURL' => admin_url( 'admin-ajax.php' ),
			'compareItemsCount' => $this->compare_cookie_items_count,
			'nonce' => wp_create_nonce( $this->plugin_name ),
			'actionSearchProducts' => 'addonify_compare_products_search_products',
			'actionRemoveProduct' => 'addonify_compare_products_remove_product',
			'actionAddProduct' => 'addonify_compare_products_add_product',			
			'actionGetCompareContent' => 'addonify_compare_products_compare_content',
		);

		// localize script.
		wp_localize_script(
			$this->plugin_name,
			'addonifyCompareProductsJSObject',
			$localize_args
		);

	}



	/**
	 * Tasks that needs to be done during "init" hook.
	 *
	 * @since    1.0.0
	 */
	public function init_callback() {

		if ( ! array_key_exists( $this->plugin_name, $_COOKIE ) ) {

			$this->set_compare_cookie();
		} 
	}


	/**
	 * Checks if compare cookie is set and get the compare cookie items.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function get_compare_cookie_items() {

		return ( array_key_exists( $this->plugin_name, $_COOKIE ) ) ? json_decode( $_COOKIE[$this->plugin_name] ) : array();
	}


	/**
	 * Checks if compare cookie is array and number of items in the compare cookie.
	 * 
	 * @since 1.0.0
	 * @return int
	 */
	public function get_compare_cookie_items_count() {

		return is_array( $this->compare_cookie_items ) ? count( $this->compare_cookie_items ) : 0;
	}


	/**
	 * Set the compare cookie with products.
	 * 
	 * @since 1.0.0
	 * @return boolean true if cookie is set, false otherwise.
	 */
	private function set_compare_cookie( $product_ids = array() ) {

		// Set browser cookie if there are products in the compare list.
		$cookies_lifetime = (int) addonify_compare_products_get_option( 'compare_products_cookie_expires' ) * DAY_IN_SECONDS;

		if ( 
			is_array( $product_ids ) &&
			count( $product_ids ) > 0 
		) {
			return setcookie( $this->plugin_name, json_encode( $product_ids ), time() + $cookies_lifetime, COOKIEPATH, COOKIE_DOMAIN );
		} else {

			return setcookie( $this->plugin_name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
		}
	}



	/**
	 * Ajax call handler to add product into the compare cookie.
	 * 
	 * @since 1.0.0
	 * @return array $response_data
	 */
	public function add_product_into_compare_cookie() {

		$response_data = array(
			'success' => false,
			'message' => '',
		);

		if ( 
			! array_key_exists( 'nonce', $_POST ) ||
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( $_POST['nonce'], $this->plugin_name )
		) {
			$response_data['message'] = __( 'Invalid security token.', 'addonify-compare-products' );	
			wp_send_json( $response_data );
		} 

		$product_id = (int) $_POST['product_id'];

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			$response_data['message'] = __( 'Invalid product ID.', 'addonify-compare-products' );
			wp_send_json( $response_data );  
		}

		if ( in_array( $product_id, $this->compare_cookie_items ) ) {
			$response_data['message'] = __( 'Product ID is already in compare list.'. 'addonify-compare-products' );
			wp_send_json( $response_data );
		}

		$this->compare_cookie_items[] = $product_id;

		if ( ! $this->set_compare_cookie( $this->compare_cookie_items ) ) {
			$response_data['message'] = __( 'Product could not be added into the compare list.', 'addonify-compare-products' );
			wp_send_json( $response_data );
		}

		$response_data['success'] = true;

		$response_data['product_image'] = $this->get_docker_product_image( $product );

		$response_data['items_count'] = $this->get_compare_cookie_items_count();

		$response_data['message'] = __( 'Product added into the compare list.', 'addonify-compare-products' );

		wp_send_json( $response_data );
	}


	/**
	 * Ajax call handler to remove product from the compare cookie.
	 * 
	 * @since 1.0.0
	 * @return array $response_data
	 */
	public function remove_product_from_compare_cookie() {

		$response_data = array(
			'success' => false,
			'message' => '',
		);

		if ( 
			! array_key_exists( 'nonce', $_POST ) ||
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( $_POST['nonce'], $this->plugin_name )
		) {
			$response_data['message'] = __( 'Invalid security token.', 'addonify-compare-products' );	
			wp_send_json( $response_data );
		} 

		$product_id = (int) $_POST['product_id'];

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			$response_data['message'] = __( 'Invalid product ID.', 'addonify-compare-products' );
			wp_send_json( $response_data );  
		}

		if ( ! in_array( $product_id, $this->compare_cookie_items ) ) {
			$response_data['message'] = __( 'Product ID is not in the compare list.'. 'addonify-compare-products' );
			wp_send_json( $response_data );
		}

		$product_id_index = array_search( $product_id, $this->compare_cookie_items );

		unset( $this->compare_cookie_items[$product_id_index] );

		$this->compare_cookie_items = array_values( $this->compare_cookie_items );

		if ( ! $this->set_compare_cookie( $this->compare_cookie_items ) ) {
			$response_data['message'] = __( 'Product could not be removed from the compare list.', 'addonify-compare-products' );
			wp_send_json( $response_data );
		}

		$response_data['success'] = true;

		$response_data['items_count'] = $this->get_compare_cookie_items_count();

		$response_data['message'] = __( 'Product removed from the compare list.', 'addonify-compare-products' );

		wp_send_json( $response_data );
	}

	
	/**
	 * Return product's image when product is added into the compare cookie.
	 * 
	 * @since 1.0.0
	 * @return string HTML markup for product image.
	 */
	public function get_docker_product_image( $product ) {
		
		return '<div class="addonify-compare-dock-components" data-product_id="' . esc_attr( $product->get_id() ) . '"><div class="sortable addonify-compare-dock-thumbnail" data-product_id="' . esc_attr( $product->get_id() ) . '"><span class="addonify-compare-dock-remove-item-btn addonify-compare-docker-remove-button" data-product_id="' . esc_attr( $product->get_id() ) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg></span>' . wp_kses_post( $product->get_image() ) . '</div></div>';
	}


	/**
	 * Ajax call handler to search products.
	 *
	 * @since    1.0.0
	 */
	public function ajax_products_search_callback() {

		// only ajax request is allowed.
		if ( ! wp_doing_ajax() ) {
			wp_die( 'Invalid Request' );
		}

		$query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		// search query is required.
		if ( empty( $query ) ) {
			wp_die( 'search query is required !' );
		}

		// verify nonce.
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->plugin_name ) ) {

			wp_die( 'nonce validation fail !' );
		}

		// skip products that are already in cookies.
		$wp_query = new WP_Query(
			array(
				's' => $query,
				'post__not_in' => $this->get_compare_cookie_items(),
				'post_type' => 'product',
			)
		);

		$this->get_templates(
			'addonify-compare-products-search-result',
			false,
			array(
				'wp_query' => $wp_query,
				'query' => $query,
			)
		);

		wp_die();
	}


	/**
	 * Generating "Compare" button
	 *
	 * @since    1.0.0
	 */
	public function render_compare_button() {

		global $product;

		$css_classes = array();

		$css_classes[] = ( is_array( $this->compare_cookie_items ) && count( $this->compare_cookie_items ) && in_array( $product->get_id(), $this->compare_cookie_items ) ) ? 'selected' : '';

		$this->get_templates(
			'addonify-compare-products-button',
			false,
			array(
				'product_id' => $product->get_id(),
				'label' => addonify_compare_products_get_option( 'compare_products_btn_label' ),
				'css_classes' => apply_filters( 'addonify_compare_products/compare_button_css_classes', $css_classes ),
			)
		);
	}



	/**
	 * Generate required markups and print it in footer of the website
	 *
	 * @since    1.0.0
	 */
	public function add_markup_into_footer_callback() {

		// do not show following template if it is a shortcode display page.
		if ( get_the_ID() === (int) addonify_compare_products_get_option( 'compare_page' ) ) {

			return;
		}

		$docker_compare_button = '';

		if ( 'popup' === addonify_compare_products_get_option( 'compare_products_display_type' ) ) {

			$docker_compare_button = '<button id="addonify-compare-dock-compare-btn">' . apply_filters( 'addonify_cp_footer_btn_label', __( 'Compare', 'addonify-compare-products' ) ) . '</button>';
		} else {

			$docker_compare_button = '<a href="' . esc_url( get_permalink( (int) addonify_compare_products_get_option( 'compare_page' ) ) ) . '"><button id="addonify-compare-dock-compare-btn-link">' . apply_filters( 'addonify_cp_footer_btn_label', __( 'Compare', 'addonify-compare-products' ) ) . '</button></a>';
		}

		

		$this->get_templates(
			'addonify-compare-products-wrapper',
			true,
			array(
				'compare_button' => $docker_compare_button,
				'product_ids' => $this->get_compare_cookie_items()
			)
		);

		if ( 'popup' === addonify_compare_products_get_option( 'compare_products_display_type' ) ) {
			$this->get_templates( 'addonify-compare-products-compare-modal-wrapper' );
		}

	}



	/**
	 * Generate contents for compare and print it
	 * Can be used in ajax requests or in shortcodes
	 *
	 * @since    1.0.0
	 */
	public function comparison_content_callback() {

		// generate contents and return as array data.
		$comparison_content = $this->generate_contents_data();

		if ( wp_doing_ajax() ) {
			$this->get_templates( 'addonify-compare-products-content', true, array( 'data' => $comparison_content ) );
			wp_die();
		} else {
			ob_start();
			$this->get_templates( 'addonify-compare-products-content', true, array( 'data' => $comparison_content ) );
			return ob_get_clean();
		}

	}


	/**
	 * Print dynamic CSS generated from settings page.
	 */
	public function dynamic_css() {

		$css_values = array(
			'--adfy_compare_products_button_color' => addonify_compare_products_get_option( 'compare_btn_text_color' ),
			'--adfy_compare_products_button_color_hover' => addonify_compare_products_get_option( 'compare_btn_text_color_hover' ),
			'--adfy_compare_products_button_bg_color' => addonify_compare_products_get_option( 'compare_btn_bck_color' ),
			'--adfy_compare_products_button_bg_color_hover' => addonify_compare_products_get_option( 'compare_btn_bck_color_hover' ),
			'--adfy_compare_products_dock_bg_color' => addonify_compare_products_get_option( 'floating_bar_bck_color' ),
			'--adfy_compare_products_dock_text_color' => addonify_compare_products_get_option( 'floating_bar_text_color' ),
			'--adfy_compare_products_dock_add_button_color' => addonify_compare_products_get_option( 'floating_bar_add_button_text_color' ),
			'--adfy_compare_products_dock_add_button_color_hover' => addonify_compare_products_get_option( 'floating_bar_add_button_text_color_hover' ),
			'--adfy_compare_products_dock_add_button_bg_color' => addonify_compare_products_get_option( 'floating_bar_add_button_bck_color' ),
			'--adfy_compare_products_dock_add_button_bg_color_hover' => addonify_compare_products_get_option( 'floating_bar_add_button_bck_color_hover' ),
			'--adfy_compare_products_dock_compare_button_color' => addonify_compare_products_get_option( 'floating_bar_compare_button_text_color' ),
			'--adfy_compare_products_dock_compare_button_color_hover' => addonify_compare_products_get_option( 'floating_bar_compare_button_text_color_hover' ),
			'--adfy_compare_products_dock_compare_button_bg_color' => addonify_compare_products_get_option( 'floating_bar_compare_button_bck_color' ),
			'--adfy_compare_products_dock_compare_button_bg_color_hover' => addonify_compare_products_get_option( 'floating_bar_compare_button_bck_color_hover' ),
			'--adfy_compare_products_search_modal_overlay_bg_color' => addonify_compare_products_get_option( 'search_modal_overlay_bck_color' ),
			'--adfy_compare_products_search_modal_bg_color' => addonify_compare_products_get_option( 'search_modal_bck_color' ),
			'--adfy_compare_products_search_modal_add_button_color' => addonify_compare_products_get_option( 'search_modal_add_btn_text_color' ),
			'--adfy_compare_products_search_modal_add_button_color_hover' => addonify_compare_products_get_option( 'search_modal_add_btn_text_color_hover' ),
			'--adfy_compare_products_search_modal_add_button_bg_color' => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color' ),
			'--adfy_compare_products_search_modal_add_button_bg_color_hover' => addonify_compare_products_get_option( 'search_modal_add_btn_bck_color_hover' ),
			'--adfy_compare_products_search_modal_close_button_color' => addonify_compare_products_get_option( 'search_modal_close_btn_text_color' ),
			'--adfy_compare_products_search_modal_close_button_color_hover' => addonify_compare_products_get_option( 'search_modal_close_btn_text_color_hover' ),
			'--adfy_compare_products_search_modal_close_button_border_color' => addonify_compare_products_get_option( 'search_modal_close_btn_border_color' ),
			'--adfy_compare_products_search_modal_close_button_border_color_hover' => addonify_compare_products_get_option( 'search_modal_close_btn_border_color_hover' ),
			'--adfy_compare_products_table_title_color' => addonify_compare_products_get_option( 'table_title_color' ),
			'--adfy_compare_products_table_title_color_hover' => addonify_compare_products_get_option( 'table_title_color_hover' ),
		);

		$css = ':root {';

		foreach ( $css_values as $key => $value ) {

			if ( $value ) {
				$css .= $key . ': ' . $value . ';';
			}
		}

		$css .= '}';

		return $css;
	}


	/**
	 * Minify the dynamic css.
	 * 
	 * @param string $css css to minify.
	 * @return string minified css.
	 */
	public function minify_css( $css ) {

		$css = preg_replace( '/\s+/', ' ', $css );
		$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
		$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
		$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

		return trim( $css );
	}

	/**
	 * Prepare data to be used in comparison table
	 *
	 * @since    1.0.0
	 */
	private function generate_contents_data() {

		if ( 
			! is_array( $this->compare_cookie_items ) ||
			count( $this->compare_cookie_items ) <= 0
		) {
			return;
		}

		$product_content = ( addonify_compare_products_get_option( 'fields_to_compare' ) ) ? json_decode( addonify_compare_products_get_option( 'fields_to_compare' ), true ) : array();

		if ( 
			! is_array( $product_content ) &&
			count( $product_content ) <= 0
		) {
			return;
		}

		$selected_products_data = array( 'product_id' => array( '0' ) );		

		if ( in_array( 'title', $product_content ) ) {
			$selected_products_data[ 'title' ] = array( __( 'Title', 'addonify-compare-products' ) );
		}

		if ( in_array( 'image', $product_content ) ) {
			$selected_products_data[ 'image' ] = array( __( 'Image', 'addonify-compare-products' ) );
		}

		if ( in_array( 'price', $product_content ) ) {
			$selected_products_data[ 'price' ] = array( __( 'Price', 'addonify-compare-products' ) );
		}

		if ( in_array( 'description', $product_content ) ) {
			$selected_products_data[ 'description' ] = array( __( 'Description', 'addonify-compare-products' ) );
		}

		if ( in_array( 'rating', $product_content ) ) {
			$selected_products_data[ 'rating' ] = array( __( 'Rating', 'addonify-compare-products' ) );
		}

		if ( in_array( 'in_stock', $product_content ) ) {
			$selected_products_data[ 'in_stock' ] = array( __( 'In Stock', 'addonify-compare-products' ) );
		}

		if ( in_array( 'add_to_cart_button', $product_content ) ) {
			$selected_products_data[ 'add_to_cart_button' ] = array( __( 'Action', 'addonify-compare-products' ) );
		}

		if ( 
			is_array( $this->compare_cookie_items ) && 
			$this->compare_cookie_items_count > 0 
		) {

			// main loop --------------.
			foreach ( $this->compare_cookie_items as $product_id ) {

				$product = wc_get_product( $product_id );

				$selected_products_data[ 'product_id' ][] = $product_id;

				if ( in_array( 'title', $product_content ) ) {

					$delete_button = '<button class="addonify-remove-compare-products addonify-compare-table-remove-btn" data-product_id="' . esc_attr( $product_id ) . '">x</button>';
					
					$selected_products_data['title'][] = '<a class="product-title-link" href="' . esc_url( $product->get_permalink() ) . '" >' . wp_kses_post( $product->get_title() ) . '</a>' . $delete_button;
				}

				if ( in_array( 'image', $product_content ) ) {
					$selected_products_data['image'][] = '<a href="' . esc_url( $product->get_permalink() ) . '" >' . wp_kses_post( $product->get_image( array(72, 72 ) ) ) . '</a>';
				}

				if ( in_array( 'price', $product_content ) ) {
					$selected_products_data['price'][] = '<span class="price">' . wp_kses_post( $product->get_price_html() ) . '</span>';
				}

				if ( in_array( 'description', $product_content ) ) {
					$selected_products_data['description'][] = wp_kses_post( $product->get_short_description() );
				}

				if ( in_array( 'rating', $product_content ) ) {
					$ratings = wc_get_rating_html( $product->get_average_rating() );
					$selected_products_data['rating'][] = ( $ratings ) ? wp_kses_post( $ratings ) : '-';
				}

				if ( in_array( 'in_stock', $product_content ) ) {
					$selected_products_data['in_stock'][] = ( $product->is_in_stock() ) ? __( 'Yes', 'addonify-compare-products' ) : __( 'No', 'addonify-compare-products' );
				}

				if ( in_array( 'add_to_cart_button', $product_content ) ) {

					$selected_products_data['add_to_cart_button'][] = do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style="" ]' );
				}
			}
		}

		return $selected_products_data;
	}



	/**
	 * Return product attributes array
	 *
	 * Used for future purpose.
	 * 
	 * @since    1.0.0
	 * @param    string $product Woocommerce product object.
	 */
	private function get_product_attributes( $product ) {

		$attributes = $product->get_attributes();

		$display_result = array();

		foreach ( $attributes as $attribute ) {

			$name = $attribute->get_name();
			if ( $attribute->is_taxonomy() ) {

				$terms = wp_get_post_terms( $product->get_id(), $name, 'all' );
				$cwtax = $terms[0]->taxonomy;
				$cw_object_taxonomy = get_taxonomy( $cwtax );

				if ( isset( $cw_object_taxonomy->labels->singular_name ) ) {
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

				$display_result[ $tax_label ] = implode( ', ', $tax_terms );

			} else {
				$display_result[ $name ] = esc_html( implode( ', ', $attribute->get_options() ) );
			}
		}

		return ! empty( $display_result ) ? $display_result : array();
	}



	/**
	 * Require proper templates for use in front end
	 *
	 * @since    1.0.0
	 * @param    string $template_name Name of the template.
	 * @param    bool   $require_once  Should use require_once or require ?.
	 * @param    array  $data          Data to pass to template.
	 */
	private function get_templates( $template_name, $require_once = true, $data = array() ) {

		// first look for template in themes/addonify/templates.
		$theme_path = get_template_directory() . '/addonify/addonify-compare-products/' . $template_name . '.php';
		$plugin_path = dirname( __FILE__ ) . '/templates/' . $template_name . '.php';
		$template_path = file_exists( $theme_path ) ? $theme_path : $plugin_path;

		// Extract keys from array to separate local variables.
		foreach ( $data as $data_key => $data_val ) {
			$$data_key = $data_val;
		}

		if ( $require_once ) {
			require_once $template_path;
		} else {
			require $template_path;
		}
	}



	/**
	 * Register shortcode to use in comparison page
	 *
	 * @since    1.0.0
	 */
	public function register_shortcode() {

		add_shortcode( 'addonify_compare_products', array( $this, 'comparison_content_callback' ) );
	}
}
