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

<div id="addonify-compare-products-table-wrapper" >
	<p id="addonify-compare-products-notice" class="addonify-compare-products-notice <?php echo ( ! empty( $data ) ) ? 'addonify-compare-hidden' : ''; ?>"><?php esc_html_e( 'Sorry! There is nothing to compare.', 'addonify-compare-products' ); ?></p>
	<?php if( $data ) { ?>
		<table id="addonify-compare-products-table" class="<?php echo ( empty( $data ) ) ? 'addonify-compare-hidden' : ''; ?>">
			<tbody>
				<?php
				foreach ( $data as $label => $content ) {
					if ( $label != 'product_id' ) {
						echo '<tr>';
						foreach ( $content as $key => $value ) {
							echo '<td class="' . ( ( $key === 0 ) ? 'acp-table-head' : 'acp-table-row-' . $key . ' acp-table-field-' . $label ) . '" data-product_id="' . esc_attr( $data['product_id'][$key] ) . '">' . wp_kses_post( $value ) . '</td>';
						}
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	<?php } ?>
	
</div>
