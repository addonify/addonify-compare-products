<?php

    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    $wp_query = $data['query'];
    while($wp_query->have_posts()):
        $wp_query->the_post();
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
				<div class="item-name"><span><?php the_title(); ?></span></div>
			</div>
			<div class="item-add" data-product_id="<?php echo esc_attr( get_the_ID() ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="12" height="12"><path fill="none" d="M0 0h24v24H0z"/><path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/></svg>
			</div>
		</li>

			<?php
		endwhile;
		wp_reset_postdata();
	else :
		?>
		<li><span>No results found for <strong><?php echo esc_html( $query ); ?></strong></span></li>
	<?php endif; ?>

</ul>
