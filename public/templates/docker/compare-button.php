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
<div class="addonify-compare-dock-components">
	<?php
	if ( empty( $compare_page_link ) ) {
		?>
		<button id="addonify-compare-dock-compare-btn" class="addonify-dock-compare-button">
			<?php echo esc_html( $button_label ); ?>
		</button>
		<?php
	} else {
		?>
		<a id="addonify-compare-dock-compare-btn-link" class="addonify-dock-compare-button" href="<?php echo esc_url( $compare_page_link ); ?>"><?php echo esc_html( $button_label ); ?></a>
		<?php
	}
	?>
</div><!-- .addonify-compare-dock-components -->
