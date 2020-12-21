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

<ul>

	<?php
	if ( $wp_query->have_posts() ) :
		while ( $wp_query->have_posts() ) :
			$wp_query->the_post();
			?>
		<li>
			<div class="item">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'thumbnail', array( 'class' => 'item-image' ) );
				}
				?>
				<div class="item-name"><?php the_title(); ?></div>
				<div class="item-add " data-product_id="<?php echo esc_attr( get_the_ID() ); ?>"><span>+</span></div>
			</div>
		</li>

			<?php
		endwhile;
		wp_reset_postdata();
	else :
		?>
		<li>No results found for <strong><?php echo esc_html( $query ); ?></strong></li>
	<?php endif; ?>

</ul>
