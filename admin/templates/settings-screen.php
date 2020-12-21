<?php
/**
 * Template for the admin part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin/templates
 */

/**
 * Template for the admin part of the plugin.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/admin/templates
 * @author     Addodnify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>

<div class="wrap">

	<h1><?php esc_html_e( 'Compare Products Options', 'addonify-compare-products' ); ?></h1>

	<div id="addonify-settings-wrapper">
			
		<ul id="addonify-settings-tabs">
			<li>
				<a href="<?php echo esc_url( $tab_url ); ?>settings" class="<?php echo ( 'settings' === $current_tab ) ? 'active' : ''; ?>" > 
					<?php esc_html_e( 'Settings', 'addonify-compare-products' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo esc_url( $tab_url ); ?>styles" class="<?php echo ( 'styles' === $current_tab ) ? 'active' : ''; ?>" > 
					<?php esc_html_e( 'Styles', 'addonify-compare-products' ); ?> 
				</a>
			</li>
		</ul>

		<?php if ( 'settings' === $current_tab ) : ?>

			<!-- settings tabs -->
			<form method="POST" action="options.php">
			
				<!-- generate nonce -->
				<?php settings_fields( 'compare_products_settings' ); ?>

				<div id="addonify-settings-container" class="addonify-content">
					<!-- display form fields -->

					<div id="addonify-settings-container" class="addonify-section ">
						<?php do_settings_sections( $this->settings_page_slug . '-settings' ); ?>
					</div>

					<div id="addonify-settings-options-container" class="addonify-section">
						<?php do_settings_sections( $this->settings_page_slug . '-settings-table-options' ); ?>
					</div>

				</div><!--addonify-settings-container-->

				<?php submit_button(); ?>

			</form>
		
		<?php elseif ( 'styles' === $current_tab ) : ?>

			<!-- styles tabs -->
			<form method="POST" action="options.php">
			
				<!-- generate nonce -->
				<?php settings_fields( 'compare_products_styles' ); ?>

				<div id="addonify-styles-container" class="addonify-content">

					<div id="addonify-style-options-container" class="addonify-section ">
						<?php do_settings_sections( $this->settings_page_slug . '-styles' ); ?>
					</div>

					<div id="addonify-content-colors-container" class="addonify-section">
						<?php do_settings_sections( $this->settings_page_slug . '-content-colors' ); ?>
					</div>
				</div><!--addonify-styles-container-->

				<?php submit_button(); ?>

			</form>

		<?php endif; ?>
	
	</div><!--addonify-settings-wrapper-->
	
</div> <!--wrap-->
