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
<div id="addonify-compare-dock-message" class="<?php echo esc_attr( implode( ' ', $css_classes ) ); ?>" >
	<?php echo esc_html( $message ); ?>
</div><!-- #addonify-compare-dock-message -->
