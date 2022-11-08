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

?>
<div id="addonify-compare-dock">	

	<?php do_action( 'addonify_compare_products_docker_message' ); ?>

	<div id="addonify-compare-dock-inner" class="<?php echo esc_attr( implode( ' ', $inner_css_classes ) ); ?>">

		<div id="addonify-compare-dock-thumbnails">
			<?php do_action( 'addonify_compare_products_docker_content' ); ?>
		</div><!-- #addonify-compare-dock-thumbnails -->

		<?php do_action( 'addonify_compare_products_docker_add_button' ); ?>

		<?php do_action( 'addonify_compare_products_docker_compare_button' ); ?>
	</div><!-- #addonify-compare-dock-inner -->

</div><!-- #addonify-compare-dock -->
