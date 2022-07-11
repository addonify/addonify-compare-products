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

<ul id="adfy-compare-search-result">

	<?php
	if ( $wp_query->have_posts() ) :
		while ( $wp_query->have_posts() ) :
			$wp_query->the_post();
			?>
		<li class="adfy-compare-search-result-item">
			<div class="item">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'thumbnail', array( 'class' => 'item-image' ) );
				}
				?>
				<div class="item-name">
					<p class="product-title"><?php the_title(); ?></p>
				</div>
			</div>
			<div class="item-add " data-product_id="<?php echo esc_attr( get_the_ID() ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path fill="none" d="M0 0h24v24H0z"/>
					<path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
				</svg>
			</div>
		</li>

		<?php
		endwhile;
		wp_reset_postdata();
	else :
		?>
		<li class="no-result">No results found for <strong><?php echo esc_html( $query ); ?></strong></li>
	<?php endif; ?>

</ul>
