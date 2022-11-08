<?php
/**
 * Template for the front end part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates/docker
 */

/**
 * Template for the front end part of the plugin.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates/docker
 * @author     Addodnify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>
<div id="addonify-compare-dock-thumbnails">
	<?php
	foreach ( $products as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			?>
			<div class="addonify-compare-dock-components" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
				<div class="addonify-compare-dock-thumbnail" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
					<span class="addonify-compare-dock-remove-item-btn addonify-compare-docker-remove-button" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path></svg>
					</span><!-- addonify-compare-dock-remove-item.addonify-compare-docker-remove-button -->
					<?php echo wp_kses_post( $product->get_image() ); ?>
				</div><!-- .addonify-compare-dock-thumbnail -->
			</div><!-- .addonify-compare-dock-components -->
			<?php
		}
	}
	?>
</div><!-- #addonify-compare-dock-thumbnails -->
