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

<div id="addonify-compare-products-table-wrapper">

	<button id="addonify-compare-close-button" class="addonify-cp-fake-button addonify-compare-all-close-btn">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
			stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
			<line x1="18" y1="6" x2="6" y2="18"></line>
			<line x1="6" y1="6" x2="18" y2="18"></line>
		</svg>
	</button>
	<p id="addonify-compare-products-notice" class="<?php echo esc_attr( implode( ' ', $message_css_classes ) ); ?>">
		<?php echo esc_html( $no_table_rows_message ); ?>
	</p><!-- #addonify-compare-products-notice -->

	<?php
	if ( $no_of_products > 1 ) {
		?>
		<table id="addonify-compare-products-table" class="<?php echo esc_attr( implode( ' ', $table_css_classes ) ); ?>">
			<tbody>
				<?php
				foreach ( $table_rows as $tablet_col => $col_content ) {
					if ( 'product_id' !== $tablet_col ) {
						echo '<tr>';
						foreach ( $col_content as $key => $value ) {
							echo '<td class="' . ( ( 0 === $key ) ? 'adfy-compare-table-head' : 'adfy-compare-table-row-' . $key . ' adfy-compare-td-field-' . $tablet_col ) . '" data-product_id="' . esc_attr( $table_rows['product_id'][ $key ] ) . '">' . ( $value ) . '</td>'; //phpcs:ignore
						}
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table><!-- #addonify-compare-products-table -->
		<?php
	}
	?>
</div><!-- #addonify-compare-products-table-wrapper -->
