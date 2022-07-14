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

	<?php if ( empty( $data ) ) : ?>
		<p><?php esc_html_e( 'Nothing to compare !', 'addonify-compare-products' ); ?></p>
	<?php else : ?>
		
		<table id="addonify-compare-products-table">
			<thead>
				<tr>
					<?php
					foreach ( $data as $key => $value ) {
						if ( 'title' === $key ) {
							echo '<th>Title</th>';
							foreach ( $value as $key1 => $value1 ) {
								if ( $value1 ) {
									echo '<th>' . wp_kses_post( $value1 ) . '</th>';
								}
							}
						}
						break;
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ( $data as $key => $value ) {

					if ( 'title' !== $key ) {
						echo '<tr>';
						echo '<td>' . esc_html( $key ) . '</td>';
						foreach ( $value as $key1 => $value1 ) {
							if ( $value1 ) {
								echo '<td  class="' . ( ( ! is_numeric( $key1 ) ? esc_html( $key1 ) : '' ) ) . '" >' . wp_kses_post( $value1 ) . '</td>';
							}
						}
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>

	<?php endif; ?>
	
</div>
