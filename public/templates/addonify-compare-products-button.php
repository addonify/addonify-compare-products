<?php
/**
 * Template for the front end part of the plugin.
 *
 * @link       https://www.addonify.com
 * @since      1.0.0
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates
 */

/**
 * Template for the front end part of the plugin.
 *
 * @package    Addonify_Compare_Products
 * @subpackage Addonify_Compare_Products/public/templates
 * @author     Addodnify <info@addonify.com>
 */

// direct access is disabled.
defined( 'ABSPATH' ) || exit;
?>
<button type="button" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-product_id="<?php echo esc_attr( $product_id ); ?>">
	<?php
	if ( ! empty( $button_icon ) ) {
		?>
		<span class="addonify-cp-icon"><?php echo addonify_compare_products_escape_svg( $button_icon ); //phpcs:ignore ?></span>
		<?php
	}
	?>
	<?php echo esc_html( $label ); ?>
</button>
<?php
