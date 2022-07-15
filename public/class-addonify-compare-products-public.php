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
	 * Cookie name
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $cookie_name = 'addonify_compare_product_selected_product_ids';

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
	 * Display type of comparison table ( modal or page )
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $display_type    Display comparison in popup or page
	 */
	private $display_type;

	/**
	 * Page id for showing comparison shortcodes
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $display_type    Display comparison in popup or page
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

		$this->enable_plugin = (int) addonify_compare_products_get_option( 'enable_product_comparison' );

		// get default display type of comparisoin table.
		// $this->display_type = $this->get_db_values( 'compare_products_display_type' );
		// $this->compare_page_id = $this->get_db_values( 'compare_page', get_option( ADDONIFY_CP_DB_INITIALS . 'page_id' ) );
		// $this->cookie_expires = $this->get_db_values( 'compare_products_cookie_expires' );

		// if ( ! is_admin() ) {

		// 	// if display type is page but page id doesnot exist ( deleted by user).
		// 	// change display type to popup.
		// 	if ( 'page' === $this->display_type ) {

		// 		if ( ! $this->compare_page_id || 'publish' != get_post_status( $this->compare_page_id ) ) {
		// 			$this->display_type = 'popup';
		// 			update_option( ADDONIFY_CP_DB_INITIALS . 'page_id' );
		// 		}
		// 	}

		// 	if ( 1 === $this->enable_plugin ) {
		// 		$this->compare_products_btn_position = $this->get_db_values( 'compare_products_btn_position' );
		// 		$this->compare_products_btn_label = $this->get_db_values( 'compare_products_btn_label' );
		// 	}

		// 	$this->register_shortcode();
		// }

	}


	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// is plugin enabled ?.
		// if ( ! $this->enable_plugin ) {
		// 	return;
		// }

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

		// do not continue if "Enable Product comparison" is not checked.
		// if ( ! $this->enable_plugin ) {
		// 	return;
		// }

		wp_enqueue_script( 'perfect-scrollbar', plugin_dir_url( __FILE__ ) . 'assets/build/js/conditional/perfect-scrollbar.min.js', null, $this->version, true );

		// js-cookie is already loaded by woocommerce.
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/build/js/public.min.js', array( 'jquery', 'jquery-ui-sortable' ), $this->version, true );

		$localize_args = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'action_get_thumbnails' => 'get_products_thumbnails',
			'action_search_items' => 'search_items',
			'action_set_cookies' => 'addonify_set_cookies',
			'action_get_compare_contents' => 'get_compare_contents',
			'cookie_expire' => $this->cookie_expires,
			'display_type' => $this->display_type,
			'comparison_page_url' => '',
			'nonce' => wp_create_nonce( $this->plugin_name ),
			'cookie_path' => COOKIEPATH,
			'cookie_domain' => COOKIE_DOMAIN,
		);

		if ( 'page' === $this->display_type ) {
			$localize_args['comparison_page_url'] = get_permalink( $this->compare_page_id );
		}

		// localize script.
		wp_localize_script(
			$this->plugin_name,
			'addonify_compare_ajax_object',
			$localize_args
		);

	}



	/**
	 * Tasks that needs to be done during "init" hook.
	 *
	 * @since    1.0.0
	 */
	public function init_callback() {

		// remove item from compare cookies.
		$this->remove_item_from_cookies();

	}



	/**
	 * Remove items from cookies
	 *
	 * @since    1.0.0
	 */
	private function remove_item_from_cookies() {

		$remove_item_id = ( isset( $_GET['addonify_cp_remove_item'] ) ) ? sanitize_text_field( wp_unslash( $_GET['addonify_cp_remove_item'] ) ) : false;
		$selected_items = ( isset( $_COOKIE[ $this->cookie_name ] ) ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $this->cookie_name ] ) ) : false;

		if ( $remove_item_id && $selected_items ) {

			$nonce = ( isset( $_GET['token'] ) ) ? sanitize_text_field( wp_unslash( $_GET['token'] ) ) : '';

			if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, $this->plugin_name ) ) {
				// show default woocommerce notice.
				wc_add_notice( 'Security token did not match. Please try again', 'error' );
			} else {

				$selected_items = explode( ',', $selected_items );
				$key = array_search( $remove_item_id, $selected_items );

				if ( false !== $key ) {
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

		// product ids is required.
		if ( ! isset( $_GET['ids'] ) ) {
			wp_die( 'product ids are missing' );
		}

		$product_ids = sanitize_text_field( wp_unslash( $_GET['ids'] ) );

		// convert into array.
		$product_ids = explode( ',', $product_ids );
		$return_data = array();

		foreach ( $product_ids as $id ) {
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

		// do not continue if "Enable Product comparison" is not checked.
		// if ( ! $this->enable_plugin ) {
		// 	return;
		// }

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

		$items_in_cookie = isset( $_COOKIE[ $this->cookie_name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $this->cookie_name ] ) ) : '';
		$items_in_cookie_ar = array();

		if ( $items_in_cookie ) {
			$items_in_cookie_ar = explode( ',', $items_in_cookie );
		}

		// skip products that are already in cookies.
		$wp_query = new WP_Query(
			array(
				's' => $query,
				'post__not_in' => $items_in_cookie_ar,
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

		$this->get_templates(
			'addonify-compare-products-button',
			false,
			array(
				'product_id' => $product->get_id(),
				'label' => addonify_compare_products_get_option( 'compare_products_btn_label' ),
				'css_class' => '',
			)
		);
	}



	/**
	 * Generate required markups and print it in footer of the website
	 *
	 * @since    1.0.0
	 */
	public function add_markup_into_footer_callback() {

		// do not continue if "Enable Product comparison" is not checked.
		// if ( ! $this->enable_plugin ) {
		// 	return;
		// }

		// do not show following template if it is a shortcode display page.
		if ( 'page' === $this->display_type && get_the_ID() === $this->compare_page_id ) {
			return;
		}

		$this->get_templates(
			'addonify-compare-products-wrapper',
			true,
			array(
				'label' => apply_filters( 'addonify_cp_footer_btn_label', __( 'Compare', 'addonify-compare-products' ) ),
			)
		);

		if ( 'popup' === $this->display_type ) {
			$this->get_templates( 'addonify-compare-products-compare-modal-wrapper' );
		}

	}



	/**
	 * Generate contents for compare and print it
	 * Can be used in ajax requests or in shortcodes
	 *
	 * @since    1.0.0
	 */
	public function get_compare_contents_callback() {

		// do not continue if "Enable Product comparison" is not checked.
		// if ( ! $this->enable_plugin ) {
		// 	return;
		// }

		// get product ids from cookies.
		$product_ids = isset( $_COOKIE[ $this->cookie_name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $this->cookie_name ] ) ) : '';

		// convert into array.
		$product_ids = explode( ',', $product_ids );

		// generate contents and return as array data.
		$compare_data = $this->generate_contents_data( $product_ids );

		if ( wp_doing_ajax() ) {
			$this->get_templates( 'addonify-compare-products-content', true, array( 'data' => $compare_data ) );
			wp_die();
		} else {
			ob_start();
			$this->get_templates( 'addonify-compare-products-content', true, array( 'data' => $compare_data ) );
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
	 * @param    string $selected_product_ids Product ids.
	 */
	private function generate_contents_data( $selected_product_ids ) {

		global $wp;

		$selected_products_data = array();
		$all_attributes = array();
		$attribute_titles = array();

		if ( is_array( $selected_product_ids ) && ( count( $selected_product_ids ) > 0 ) ) {

			$show_attributes = intval( $this->get_db_values( 'show_attributes' ) );

			if ( $show_attributes ) {

				// prepare array to fetch attributes easily later.

				foreach ( $selected_product_ids as $product_id ) {
					$all_attributes[ $product_id ] = $this->get_product_attributes( wc_get_product( $product_id ) );
				}

				foreach ( $all_attributes as $attr_key => $attr_val ) {
					if ( is_array( $attr_val ) ) {
						foreach ( $attr_val as $attr_key1 => $attr_val1 ) {
							if ( ! in_array( $attr_key1, $attribute_titles ) ) {
								$attribute_titles[] = $attr_key1;
							}
						}
					}
				}
			}

			// main loop --------------.
			foreach ( $selected_product_ids as $product_id ) {

				$product = wc_get_product( $product_id );
				$parent_product = false;

				if ( ! $product ) {
					continue;
				}

				if ( $product->is_type( 'variation' ) ) {
					$parent_product = wc_get_product( $product->get_parent_id() );
				}

				if ( intval( $this->get_db_values( 'show_product_title' ) ) ) {

					//if ( wp_doing_ajax() ) {
					//	$delete_btn = '<a href="#" class="addonify-footer-remove" data-product_id="' . esc_attr( $product_id ) . '">' . apply_filters( 'addonify_cp_remove_item_btn', '<button>x</button>' ) . '</a>';
					//} else {
					//	$action_url = add_query_arg(
					//		array(
					//			'addonify_cp_remove_item' => $product_id,
					//			'token' => wp_create_nonce( $this->plugin_name ),
					//		),
					//		home_url( $wp->request )
					//	);

					//	$delete_btn = '<a href="' . $action_url . '" class="addonify-footer-remove">' . apply_filters( 'addonify_cp_remove_item_btn', '<button>x</button>' ) . '</a>';
					//}

					$selected_products_data['a_title'][] = '<a href="' . $product->get_permalink() . '" >' . wp_strip_all_tags( $product->get_title() ) . '</a>' . $delete_btn;
				}

				if ( intval( $this->get_db_values( 'show_product_image' ) ) ) {
					$selected_products_data['b_Image'][] = '<a href="' . $product->get_permalink() . '" >' . $product->get_image( get_option( '_wooscp_image_size', 'wooscp-large' ), array( 'draggable' => 'false' ) ) . '</a>';
				}

				if ( intval( $this->get_db_values( 'show_product_price' ) ) ) {
					$selected_products_data['c_Price'][] = '<span class="price">' . $product->get_price_html() . '</span>';
				}

				if ( intval( $this->get_db_values( 'show_product_excerpt' ) ) ) {
					if ( $product->is_type( 'variation' ) ) {
						$selected_products_data['d_Description'][] = $product->get_description();
					} else {
						$selected_products_data['d_Description'][] = $product->get_short_description();
					}
				}

				if ( intval( $this->get_db_values( 'show_product_rating' ) ) ) {
					$ratings = wc_get_rating_html( $product->get_average_rating() );
					$selected_products_data['e_Rating'][] = ( $ratings ) ? $ratings : '-';
				}

				if ( intval( $this->get_db_values( 'show_stock_info' ) ) ) {
					$selected_products_data['f_In Stock'][] = ( $product->is_in_stock() ) ? __( 'Yes', 'addonify-compare-products' ) : __( 'No', 'addonify-compare-products' );
				}

				if ( $show_attributes ) {

					foreach ( $attribute_titles as $attr ) {
						$selected_products_data[ 'g_' . $attr ][] = ( isset( $all_attributes[ $product_id ][ $attr ] ) ) ? $all_attributes[ $product_id ][ $attr ] : '-';
					}
				}

				if ( intval( $this->get_db_values( 'show_add_to_cart_btn' ) ) ) {
					$selected_products_data['h_Add to Cart'][] = do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style="" ]' );
				}
			}
		}

		ksort( $selected_products_data );

		$selected_products_data_new = array();

		foreach ( $selected_products_data as $key => $value ) {
			$selected_products_data_new[ substr( $key, 2 ) ] = $value;
		}

		$selected_products_data = $selected_products_data_new;

		// create atleast 3 <td> in frontend table if display type is popup.
		if ( 'popup' === $this->display_type ) {

			foreach ( $selected_products_data as $key => $value ) {

				if ( 'Image' === $key ) {
					$key_placeholder = 'placeholder-image';
				} else {
					$key_placeholder = 'placeholder-default';
				}

				if ( 1 === count( $selected_products_data[ $key ] ) ) {
					$selected_products_data[ $key ][ $key_placeholder . ' ' . $key_placeholder . '-1' ] = '';
					$selected_products_data[ $key ][ $key_placeholder . ' ' . $key_placeholder . '-2' ] = '';
				} elseif ( 2 === count( $selected_products_data[ $key ] ) ) {
					$selected_products_data[ $key ][ $key_placeholder ] = '';
				}
			}
		}

		return $selected_products_data;

	}



	/**
	 * Return product attributes array
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
	private function register_shortcode() {
		add_shortcode( 'addonify_compare_products', array( $this, 'get_compare_contents_callback' ) );
	}
}
