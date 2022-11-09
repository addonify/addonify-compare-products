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
 * @author     Addodnify <info@addonify.com>
 */
class Addonify_Compare_Products_Admin {

	/**
	 * Settings page slug
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $settings_page_slug Default settings page slug for this plugin
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
		$this->version     = $version;
	}

	/**
	 * Initialize admin hooks.
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {

		// Enqueue admin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Display admin notice if WooCommerce is not active.
		if ( ! class_exists( 'WooCommerce' ) ) {

			add_action( 'admin_notices', array( $this, 'woocommerce_not_active_notice' ) );
		}

		// Register admin menu in the dashboard.
		add_action( 'admin_menu', array( $this, 'add_menu_callback' ), 20 );

		// Add a custom link in plugins.php page in wp-admin.
		add_action( 'plugin_action_links', array( $this, 'custom_plugin_link_callback' ), 10, 2 );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->settings_page_slug ) { //phpcs:ignore
			global $wp_styles;

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/admin.css', array(), $this->version, 'all' );
		}
	}



	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script(
			"{$this->plugin_name}-manifest",
			plugin_dir_url( __FILE__ ) . 'assets/js/manifest.js',
			null,
			$this->version,
			true
		);

		wp_register_script(
			"{$this->plugin_name}-vendor",
			plugin_dir_url( __FILE__ ) . 'assets/js/vendor.js',
			array( "{$this->plugin_name}-manifest" ),
			$this->version,
			true
		);

		wp_register_script(
			"{$this->plugin_name}-main",
			plugin_dir_url( __FILE__ ) . 'assets/js/main.js',
			array( 'lodash', "{$this->plugin_name}-vendor", 'wp-i18n', 'wp-api-fetch' ),
			$this->version,
			true
		);

		if (
			isset( $_GET['page'] ) && //phpcs:ignore
			$_GET['page'] == $this->settings_page_slug //phpcs:ignore
		) {
			wp_enqueue_script( "{$this->plugin_name}-manifest" );

			wp_enqueue_script( "{$this->plugin_name}-vendor" );

			wp_enqueue_script( "{$this->plugin_name}-main" );

			wp_localize_script(
				"{$this->plugin_name}-main",
				'ADDONIFY_COMPARE_PRODUCTS_LOCOLIZER',
				array(
					'admin_url'      => esc_url( admin_url( '/' ) ),
					'ajax_url'       => esc_url( admin_url( 'admin-ajax.php' ) ),
					'rest_namespace' => 'addonify_compare_products_options_api',
					'version_number' => $this->version,
				)
			);
		}

		wp_set_script_translations( "{$this->plugin_name}-main", $this->plugin_name );
	}



	/**
	 * Generate admin menu for this plugin
	 *
	 * @since    1.0.0
	 */
	public function add_menu_callback() {

		global $admin_page_hooks;

		$parent_menu_slug = array_search( 'addonify', $admin_page_hooks, true );

		if ( ! $parent_menu_slug ) {

			add_menu_page(
				'Addonify Settings',
				'Addonify',
				'manage_options',
				$this->settings_page_slug,
				array( $this, 'get_settings_screen_contents' ),
				'dashicons-superhero',
				70
			);

			add_submenu_page(
				$this->settings_page_slug,
				'Compare Settings',
				'Compare',
				'manage_options',
				$this->settings_page_slug,
				array( $this, 'get_settings_screen_contents' ),
				0
			);
		} else {

			add_submenu_page(
				$parent_menu_slug,
				'Compare Settings',
				'Compare',
				'manage_options',
				$this->settings_page_slug,
				array( $this, 'get_settings_screen_contents' ),
				0
			);
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
		?>
		<div id="___adfy-compare-products-app___"></div>
		<?php
	}

	/**
	 * Show error message if woocommerce is not active
	 *
	 * @since    1.0.0
	 */
	public function woocommerce_not_active_notice() {

		require dirname( __FILE__ ) . '/templates/woocommerce-not-active-notice.php';
	}
}
