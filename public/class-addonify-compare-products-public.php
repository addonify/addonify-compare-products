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
 * @author     Adodnify <info@addonify.com>
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
	 * Cookie expire time
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $cookie_expires;


	/**
	 * Cookie name
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $cookie_name = 'addonify_compare_product_selected_product_ids';



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

		// ajax action is also considered as admin action.
		$this->enable_plugin = intval( $this->get_db_values( 'enable_product_comparision', 1 ) );

		// get default display type of comparisoin table.
		$this->display_type = $this->get_db_values( 'compare_products_display_type', 'popup' );
		$this->compare_page_id = get_option( ADDONIFY_CP_DB_INITIALS . 'page_id' );
		$this->cookie_expires  $this->get_db_values( 'compare_products_cookie_expires', 'browser' );
		
		if ( ! is_admin() ) {
			
			// if display type is page but page id doesnot exist ( deleted by user).
			// change display type to popup.
			if ( 'page' === $this->display_type ) {
				$this->compare_page_id = get_option( ADDONIFY_CP_DB_INITIALS . 'page_id');

				if ( ! $this->compare_page_id || 'publish' != get_post_status( $this->compare_page_id ) ) {
					$this->display_type = 'popup';
					update_option( ADDONIFY_CP_DB_INITIALS .'page_id');
				}
			}

			if ( 1 === $this->enable_plugin ) {
				$this->compare_products_btn_position = $this->get_db_values( 'compare_products_btn_position', 'after_add_to_cart' );
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
		if ( ! $this->enable_plugin ) {
			return;
		}

		if ( is_rtl() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-compare-public-rtl.css', array(), $this->version );
		} else {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/css/addonify-compare-public.css', array(), time() );
		}
	}


	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// do not continue if "Enable Product Comparision" is not checked.
		if ( ! $this->enable_plugin ) {
			return;
		}

		// js-cookie is already loaded by woocommerce.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/addonify-compare-public.min.js', array( 'jquery', 'jquery-ui-sortable' ), time(), false );

		$localize_args = array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'action_get_thumbnails' => 'get_products_thumbnails',
			'action_search_items' => 'search_items',
			'action_set_cookies' => 'addonify_set_cookies',
			'action_get_compare_contents' => 'get_compare_contents',
			'cookie_expire' => $this->cookie_expires,
			'display_type' => $this->display_type,
			'comparision_page_url' => '',
			'cookie_path' => COOKIEPATH,
			'cookie_domain' => COOKIE_DOMAIN,
		);

		if ( 'page' === $this->display_type ) {
			$localize_args['comparision_page_url'] = get_permalink( $this->get_db_values( 'page_id' ) );
		}

		// localize script.
		wp_localize_script( 
			$this->plugin_name, 
			'addonify_compare_ajax_object', 
			$localize_args			
		);

	}


	// tasks that needs to be done during "init".
	public function init_callback() {
		
		// ------------------------------------------------------------
		// remove item from compare cookies
		// ------------------------------------------------------------

		$remove_item_id	= ( isset( $_GET['addonify_cp_remove_item'] ) ) ? $_GET['addonify_cp_remove_item'] : false;
		$selected_items = ( isset( $_COOKIE[ $this->cookie_name ] ) ) ? $_COOKIE[ $this->cookie_name ] : false;
		
		if ( $remove_item_id  && $selected_items ) {

			$nonce = ( isset( $_GET['token'] ) ) ? $_GET['token'] : '';
			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->plugin_name ) ) {
				// show default woocommerce notice.
				wc_add_notice( 'Security token did not match. Please try again', 'error' );
			} else {

				$selected_items = explode( ',', $selected_items );
	
				if ( ( $key = array_search( $remove_item_id, $selected_items ) ) !== false ) {
					unset( $selected_items[ $key ] );
				}
	
				$new_cookie_data = implode( ',', $selected_items );
	
				if ( 'browser' === $this->cookie_expires ) {
					$expire_time = 0;
				} else {
					$expire_time = time() + ( intval( $this->cookie_expires ) * DAY_IN_SECONDS );
				}
				
				if ( empty( $new_cookie_data ) ) {
					unset( $_COOKIE[ $this->cookie_name ] ); 
				}
	
				setcookie( $this->cookie_name, $new_cookie_data, $expire_time, COOKIEPATH, COOKIE_DOMAIN );

				$referrer = ! empty( wp_get_referer() ) ? wp_get_referer() : get_the_permalink( $this->compare_page_id );
				$referrer = remove_query_arg( array( 'addonify_cp_remove_item', 'token' ), $referrer );

				wp_redirect( $referrer );
				exit;
			}
			
		}
 
	}

	
	
	/**
	 * Get thumbnails from product ids.
	 * Accepts only Ajax request
	 *
	 * @since    1.0.0
	 */
	public function get_products_thumbnails_callback() {

		// only ajax request is allowed.
		if ( ! wp_doing_ajax() ) {
			wp_die( 'Invalid Request' );
		}

		// we are accepting ids as GET parameter rather than cookies.
		// because application can also call for single thumbnail.

		// product ids is required
		if ( ! isset( $_GET['ids'] ) ) wp_die( 'product ids are missing' );

		$product_ids = $_GET['ids'];

		// convert into array.
		$product_ids = explode( ',', $product_ids );
		$return_data = array();

		foreach( $product_ids as $id ) {
			$return_data[ $id ] = get_the_post_thumbnail_url( $id, 'thumbnail' );
		}

		echo json_encode( $return_data );
		wp_die();

	}


	
	/**
	 * Generate contents according to search query provided with ajax requests 
	 * Accepts only Ajax requests
	 *
	 * @since    1.0.0
	 */
	public function search_items_callback() {

		// do not continue if "Enable Product Comparision" is not checked.
		if ( ! $this->enable_plugin ) {
			return;
		}

		// only ajax request is allowed.
		if ( ! wp_doing_ajax() ) {
			wp_die( 'Invalid Request' );
		}

		// search query is required.
		if ( ! isset( $_GET['query'] ) ) {
			wp_die( 'search query is required !' );
		}
		
		$query = isset( $_GET['query'] ) ? sanitize_text_field( wp_unslash( $_GET['query'] ) ) : '';
		$items_in_cookie_ar = array();
		$items_in_cookie = $_COOKIE[ $this->cookie_name ];

		if ( $items_in_cookie ) {
			$items_in_cookie_ar = explode(',', $items_in_cookie);
		}

		// skip products that are already in cookies.
		$wp_query = new WP_Query( array( 's' => $query, 'post__not_in' =>  $items_in_cookie_ar, 'post_type' => 'product' ) );

		echo '<ul>';

		if ( $wp_query->have_posts() ) {
			$this->get_templates('addonify-compare-products-search-result', false, array( 'query' => $wp_query) );
		} else {
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
	public function show_compare_products_btn_after_add_to_cart_btn_callback() {

		if ( $this->compare_products_btn_position == 'after_add_to_cart'  ) {
			$this->show_compare_btn_aside_add_to_cart_btn_callback();
		}
	}

	

	
	/**
	 * Show compare button before "add to cart" button
	 *
	 * @since    1.0.0
	 */
	public function show_compare_products_btn_before_add_to_cart_btn_callback() {
		if ( $this->compare_products_btn_position == 'before_add_to_cart'  ) {
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
		if ( ! $this->enable_plugin ) return;

		global $product;
		$product_id = $product->get_id();

		// show compare btn after add to cart button
		if ( $this->compare_products_btn_label ) {

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
	public function show_compare_products_btn_aside_image_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if ( ! $this->enable_plugin ) return;

		global $product;
		$product_id = $product->get_id();

		if ( $this->compare_products_btn_position == 'overlay_on_image' && $this->compare_products_btn_label ) {
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
	public function add_markup_into_footer_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if ( ! $this->enable_plugin ) return;

		// do not show following template if it is a shortcode display page
		if ( $this->display_type == 'page' && $this->compare_page_id == get_the_ID() ) return;

		ob_start();

		$this->get_templates( 'addonify-compare-products-wrapper', true, array( 'label' => __( 'Compare', 'addonify-compare-products' ) ) );

		if ( $this->display_type == 'popup' ) {
			$this->get_templates( 'addonify-compare-products-compare-modal-wrapper' );
		}

		echo ob_get_clean();
	}


	
	/**
	 * Generate contents for compare and print it
	 * Can be used in ajax requests or in shortcodes
	 *
	 * @since    1.0.0
	 */
	public function get_compare_contents_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if ( ! $this->enable_plugin ) return;


		// get product ids from cookies
		$product_ids = isset( $_COOKIE[ $this->cookie_name ] ) ? $_COOKIE[ $this->cookie_name ] : '';

		// convert into array
		$product_ids = explode( ',', $product_ids );

		// generate contents and return as array data
		$compare_data = $this->generate_contents_data( $product_ids );


		ob_start();

		$this->get_templates( 'addonify-compare-products-content', true, array( 'data' => $compare_data ) );
		
		if ( wp_doing_ajax() ) {
			echo ob_get_clean();
			wp_die();
		}
		else {
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

		global $wp;

		$selected_products_data = array();
		$all_attributes			= array();
		$attribute_titles		= array();
		
		if ( is_array( $selected_product_ids ) && ( count( $selected_product_ids ) > 0 ) ) {

			$show_attributes = (int) $this->get_db_values( 'show_attributes', 1 );
			

			// prepare array to fetch attributes easily later

			if ( $show_attributes ) {

				foreach ( $selected_product_ids as $product_id ) {
					$all_attributes[ $product_id ] = $this->get_product_attributes( wc_get_product( $product_id ) );
				}

				foreach( $all_attributes as $attr_key => $attr_val ) {
					if ( is_array( $attr_val ) ) {
						foreach( $attr_val as $attr_key1 => $attr_val1 ) {
							if ( ! in_array( $attr_key1, $attribute_titles ) ) $attribute_titles[] = $attr_key1;
						}
					}
				}
			}

			
			// main loop --------------
			foreach ( $selected_product_ids as $product_id ) {

				$product = wc_get_product( $product_id );
				$parent_product = false;

				if ( ! $product )  continue;

				if ( $product->is_type( 'variation' ) ) {
					$parent_product = wc_get_product( $product->get_parent_id() );
				}


				if ( (int) $this->get_db_values( 'show_product_title', 1 ) ) {

					
					if ( wp_doing_ajax() ) {
						$delete_btn = '<a href="#" class="addonify-footer-remove" data-product_id="'. esc_attr( $product_id ) .'">' . apply_filters( 'addonify_cp_remove_item_btn', '<button>x</button>' ) . '</a>';
					}
					else {
						$action_url =  add_query_arg( 
							array( 
								'addonify_cp_remove_item' 	=> $product_id,
								'token'										=> wp_create_nonce( $this->plugin_name )
							),  
							home_url( $wp->request )
						);

						$delete_btn = '<a href="'. $action_url .'" class="addonify-footer-remove">' . apply_filters( 'addonify_cp_remove_item_btn', '<button>x</button>' ) . '</a>';
					}

								
					$selected_products_data['a_title'][] = '<a href="' . $product->get_permalink() . '" >' . wp_strip_all_tags( $product->get_title() ) . '</a>' . $delete_btn;
				}


				if ( (int) $this->get_db_values( 'show_product_image', 1 ) ) {
					$selected_products_data['b_Image'][] =  '<a href="' . $product->get_permalink() . '" >' . $product->get_image( get_option( '_wooscp_image_size', 'wooscp-large' ), array( 'draggable' => 'false' ) ) . '</a>';
				}


				if ( (int) $this->get_db_values( 'show_product_price', 1 ) ) {
					$selected_products_data['c_Price'][] =  $product->get_price_html();
				}

				if ( (int) $this->get_db_values( 'show_product_excerpt', 1 ) ) {
					if ( $product->is_type( 'variation' ) ) {
						$selected_products_data['d_Description'][] =  $product->get_description();
					} else {
						$selected_products_data['d_Description'][] =  $product->get_short_description();
					}
				}

				if ( (int) $this->get_db_values( 'show_product_rating', 1 ) ) {
					$selected_products_data['e_Rating'][] =  wc_get_rating_html( $product->get_average_rating() );
				}


				if ( (int) $this->get_db_values( 'show_stock_info', 1 ) ) {
					$selected_products_data['f_Stock'][] =  wc_get_stock_html( $product );
				}			


				if ( $show_attributes ) {

					foreach( $attribute_titles as $attr ) {
						$selected_products_data[ 'g_' .$attr ][] =  ( isset( $all_attributes[$product_id][$attr] ) ) ? $all_attributes[$product_id][$attr] : '-';
					}

				}


				if ( (int) $this->get_db_values( 'show_add_to_cart_btn', 1 ) ) {
					$selected_products_data['h_Add to Cart'][] =   do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style="" ]' );
				}

			}

		}


		ksort( $selected_products_data );

		$selected_products_data_new = array();

		foreach($selected_products_data as $key => $value) {
			$selected_products_data_new[ substr( $key, 2 ) ] = $value;
		}


		$selected_products_data = $selected_products_data_new;


		// create atleast 3 <td> in frontend table if display type is popup
		if ( $this->display_type == 'popup' ) {
			foreach( $selected_products_data as $key => $value) {

				if ( $key == 'Image' ) {
					$key_placeholder = 'placeholder-image';
				}
				else {
					$key_placeholder = 'placeholder-default';
				}

				if ( count( $selected_products_data[$key] ) === 1) {
					$selected_products_data[$key][$key_placeholder . ' ' . $key_placeholder .'-1'] = '';
					$selected_products_data[$key][$key_placeholder . ' ' . $key_placeholder .'-2'] = '';
				}

				if ( count( $selected_products_data[$key] ) === 2) {
					$selected_products_data[$key][$key_placeholder] = '';
				}
				
			}
		}

		return $selected_products_data;

	}



	/**
	 * Return product attributes array
	 *
	 * @since    1.0.0
	 * @param      string    $product       woocommerce product object
	 */
	private function get_product_attributes( $product ) {

		$attributes = $product->get_attributes();

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

		return ( ! empty( $display_result ) )  ? $display_result : array() ;
	}

	
	
	/**
	 * Generate custom style tag and print it in header of the website
	 *
	 * @since    1.0.0
	 */
	public function generate_custom_styles_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		// do not continue if plugin styles are disabled by user
		if ( ! $this->enable_plugin || ! $this->get_db_values( 'load_styles_from_plugin' ) ) return;


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
			'#addonify-compare-products-table th a' => array(
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
		if ( $custom_styles_output || $custom_css ) {
			echo "<style id=\"addonify-compare-products-styles\"  media=\"screen\"> \n" . $custom_styles_output .  $custom_css . "\n </style>\n";
		}

	}


	/**
	 * Generate style markups
	 *
	 * @since    1.0.0
	 * @param    $style_args    Style args to be processed
	 */
	private function generate_styles_markups( $style_args ) {
		$custom_styles_output = '';
		foreach($style_args as $css_sel => $property_value) {

			$properties = '';

			foreach( $property_value as $property => $db_field) {

				$css_unit = '';

				if ( is_array($db_field) ) {
					$db_value = $this->get_db_values( $db_field[0] );
					$css_unit = $db_field[1];
				}
				else {
					$db_value = $this->get_db_values( $db_field );
				}
					
				if ( $db_value ) {
					$properties .=  $property . ': ' . $db_value . $css_unit . '; ';
				}

			}
			
			if ( $properties ) {
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
	public function addonify_overlay_container_start_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if ( ! $this->enable_plugin) return;

		// do not continue if overlay is already added by another addonify plugin
		if ( defined('ADDONIFY_OVERLAY_IS_ADDED') && ADDONIFY_OVERLAY_ADDED_BY != 'compare_products' ) return;
		
		if ( $this->compare_products_btn_position == 'overlay_on_image' ) {

			if ( ! defined('ADDONIFY_OVERLAY_IS_ADDED')) {
				define('ADDONIFY_OVERLAY_IS_ADDED', 1);
			}

			if ( ! defined('ADDONIFY_OVERLAY_ADDED_BY')) {
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
	public function addonify_overlay_container_end_callback() {

		// do not continue if "Enable Product Comparision" is not checked
		if ( ! $this->enable_plugin  ) return;

		// do not continue of overlay is alrady added by another addonify plugin
		if ( defined('ADDONIFY_OVERLAY_IS_ADDED') && ADDONIFY_OVERLAY_ADDED_BY != 'compare_products' ) return;
		
		if ( $this->compare_products_btn_position == 'overlay_on_image' ) {
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
	private function get_db_values($field_name, $default = NULL ) {
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
	private function get_templates( $template_name, $require_once = true, $data = array() ) {

		// first look for template in themes/addonify/templates
		$theme_path = get_template_directory() . '/addonify/addonify-compare-products/' . $template_name .'.php';
		$plugin_path = dirname( __FILE__ ) .'/templates/' . $template_name .'.php';
		$template_path = file_exists( $theme_path ) ? $theme_path : $plugin_path;

		extract($data);

		if ( $require_once ) {
			require_once $template_path;
		}
		else {
			require $template_path;
		}
	
	}

	

	/**
	 * Register shortcode to use in comparision page
	 *
	 * @since    1.0.0
	 */
	private function register_shortcode() {
		add_shortcode( 'addonify-compare-products', array( $this, 'get_compare_contents_callback' ) );
	}
	

}
