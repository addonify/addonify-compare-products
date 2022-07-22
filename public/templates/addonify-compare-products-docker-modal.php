<?php 
?>
<div id="addonify-compare-dock">	

	<?php do_action( 'addonify_compare_products/docker_message' ); ?>

	<div id="addonify-compare-dock-inner" class="<?php echo esc_attr( implode( ' ', $inner_css_classes ) ); ?>">

		<div id="addonify-compare-dock-thumbnails">
			<?php do_action( 'addonify_compare_products/docker_content' ); ?>
		</div><!-- #addonify-compare-dock-thumbnails -->

		<?php do_action( 'addonify_compare_products/docker_add_button' ); ?>

		<?php do_action( 'addonify_compare_products/docker_compare_button' ); ?>
	</div><!-- #addonify-compare-dock-inner -->

</div><!-- #addonify-compare-dock -->