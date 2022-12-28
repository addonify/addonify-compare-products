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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Initialize public hooks.
	 *
	 * @since 1.0.0
	 */
	public function public_init() {

		if (
			! class_exists( 'WooCommerce' ) ||
			(int) addonify_compare_products_get_option( 'enable_product_comparison' ) !== 1
		) {
			return;
		}

		// Register scripts and styles for the frontend.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add the compare button to the product catalog.
		switch ( addonify_compare_products_get_option( 'compare_products_btn_position' ) ) {
			case 'before_add_to_cart':
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'render_compare_button' ), 5 );
				break;
			default:
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'render_compare_button' ), 15 );
		}

		// Add custom markup into footer to display comparison modal.
		add_action( 'wp_footer', array( $this, 'add_markup_into_footer_callback' ) );

		// Ajax callback handler to display compare dock and compare modal.
		add_action( 'wp_ajax_addonify_compare_products_init', array( $this, 'initial_compare_display' ) );
		add_action( 'wp_ajax_nopriv_addonify_compare_products_init', array( $this, 'initial_compare_display' ) );

		// Ajax callback handler to add product into the compare list.
		add_action( 'wp_ajax_addonify_compare_products_add_product', array( $this, 'add_product_into_compare_cookie' ) );
		add_action( 'wp_ajax_nopriv_addonify_compare_products_add_product', array( $this, 'add_product_into_compare_cookie' ) );

		// Ajax callback handler to search products.
		add_action( 'wp_ajax_addonify_compare_products_search_products', array( $this, 'ajax_products_search_callback' ) );
		add_action( 'wp_ajax_nopriv_addonify_compare_products_search_products', array( $this, 'ajax_products_search_callback' ) );

		// Ajax callback handler to render comparison table in the compare modal.
		add_action( 'wp_ajax_addonify_compare_products_compare_content', array( $this, 'render_comparison_content' ) );
		add_action( 'wp_ajax_nopriv_addonify_compare_products_compare_content', array( $this, 'render_comparison_content' ) );

		// Register shortocode to display comparison table in the comparison page.
		add_shortcode( 'addonify_compare_products', array( $this, 'render_shortcode_content' ) );
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
			'ajaxURL'                 => admin_url( 'admin-ajax.php' ),
			'nonce'                   => wp_create_nonce( $this->plugin_name ),
			'actionInit'              => 'addonify_compare_products_init',
			'actionSearchProducts'    => 'addonify_compare_products_search_products',
			'actionAddProduct'        => 'addonify_compare_products_add_product',
			'actionGetCompareContent' => 'addonify_compare_products_compare_content',
			'localDataExpiresIn'      => (int) addonify_compare_products_get_option( 'compare_products_cookie_expires' ),
			'messageOnNoProducts'     => esc_html__( 'No products to compare' ),
			'messageOnOneProduct'     => esc_html__( 'More than one products required for comparison.' ),
			'thisSiteUrl'             => get_bloginfo( 'url' ),
		);

		// localize script.
		wp_localize_script(
			$this->plugin_name,
			'addonifyCompareProductsJSObject',
			$localize_args
		);

	}

	/**
	 * Returns initial html for compare dock and compare modal.
	 *
	 * @since 1.1.0
	 */
	public function initial_compare_display() {

		$response_data = array(
			'success' => false,
		);

		if (
			isset( $_POST['nonce'] ) ||
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $this->plugin_name )
		) {
			ob_start();
			do_action( 'addonify_compare_products_docker_content' );
			$response_data['html']['#addonify-compare-dock-thumbnails'] = ob_get_clean();

			ob_start();
			do_action( 'addonify_compare_products_comparison_content' );
			$response_data['compareModalContent'] = ob_get_clean();

			$response_data['success'] = true;

		} else {
			$response_data['message'] = __( 'Invalid security token.', 'addonify-compare-products' );
		}

		wp_send_json( $response_data );
	}

	/**
	 * Ajax call handler to add product into the compare cookie.
	 *
	 * @since 1.0.0
	 */
	public function add_product_into_compare_cookie() {

		$response_data = array(
			'success' => false,
			'message' => '',
		);

		if (
			! isset( $_POST['nonce'] ) ||
			! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $this->plugin_name )
		) {
			$response_data['message'] = __( 'Invalid security token.', 'addonify-compare-products' );
			wp_send_json( $response_data );
		}

		$product_id = (int) $_POST['product_id']; //phpcs:ignore

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			$response_data['message'] = __( 'Invalid product ID.', 'addonify-compare-products' );
			wp_send_json( $response_data );
		}

		ob_start();
		do_action( 'addonify_compare_products_comparison_content' );
		$response_data['compareModalContent'] = ob_get_clean();

		$response_data['success'] = true;

		$response_data['product_image'] = $this->get_docker_product_image( $product );

		$response_data['message'] = __( 'Product added into the compare list.', 'addonify-compare-products' );

		wp_send_json( $response_data );
	}


	/**
	 * Return product's image when product is added into the compare cookie.
	 *
	 * @since 1.0.0
	 * @param object $product Product object.
	 * @return string HTML markup for product image.
	 */
	public function get_docker_product_image( $product ) {

		return '<div class="addonify-compare-dock-components" data-product_id="' .
			esc_attr( $product->get_id() ) . '"><div class="sortable addonify-compare-dock-thumbnail" data-product_id="' .
			esc_attr( $product->get_id() ) . '"><span class="addonify-compare-dock-remove-item-btn addonify-compare-docker-remove-button" data-product_id="' .
			esc_attr( $product->get_id() ) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg></span>' .
			wp_kses_post( $product->get_image() ) . '</div></div>';
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

		$products_not_in = isset( $_POST['product_ids'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['product_ids'] ) ) ) : array();

		// skip products that are already in cookies.
		$wp_query = new WP_Query(
			array(
				's'            => $query,
				'post__not_in' => $products_not_in,
				'post_type'    => 'product',
			)
		);

		do_action(
			'addonify_compare_products_search_result',
			array(
				'wp_query' => $wp_query,
				'query'    => $query,
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

		do_action( 'addonify_compare_products_compare_button' );
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

		do_action( 'addonify_compare_products_docker_modal' );

		do_action( 'addonify_compare_products_search_modal' );

		do_action( 'addonify_compare_products_comparison_modal' );
	}



	/**
	 * Generate contents for compare and print it
	 * Can be used in ajax requests or in shortcodes
	 *
	 * @since    1.0.0
	 */
	public function render_comparison_content() {

		if ( wp_doing_ajax() ) {

			do_action( 'addonify_compare_products_comparison_content' );
			wp_die();
		} else {

			ob_start();
			do_action( 'addonify_compare_products_comparison_content' );
			return ob_get_clean();
		}
	}

	/**
	 * For rendering shortcode.
	 */
	public function render_shortcode_content() {

		ob_start();
		?>
		<div id="addonify-compare-products-comparison-table-on-page"></div>
		<?php
		return ob_get_clean();
	}


	/**
	 * Print dynamic CSS generated from settings page.
	 */
	public function dynamic_css() {

		$css_values = array(
			'--adfy_compare_products_button_color'       => addonify_compare_products_get_option( 'compare_btn_text_color' ),
			'--adfy_compare_products_button_color_hover' => addonify_compare_products_get_option( 'compare_btn_text_color_hover' ),
			'--adfy_compare_products_button_bg_color'    => addonify_compare_products_get_option( 'compare_btn_bck_color' ),
			'--adfy_compare_products_button_bg_color_hover' => addonify_compare_products_get_option( 'compare_btn_bck_color_hover' ),
			'--adfy_compare_products_dock_bg_color'      => addonify_compare_products_get_option( 'floating_bar_bck_color' ),
			'--adfy_compare_products_dock_text_color'    => addonify_compare_products_get_option( 'floating_bar_text_color' ),
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
			'--adfy_compare_products_table_title_color'  => addonify_compare_products_get_option( 'table_title_color' ),
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
}
