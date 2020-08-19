<?php

    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    $wp_query = $data['query'];
    while( $wp_query->have_posts() ):
        $wp_query->the_post();
?>

    <li>
        <div class="item">
            <?php 
                if( has_post_thumbnail()){
                    the_post_thumbnail( 'thumbnail', array( 'class' => 'item-image') );
                }
            ?>
            <div class="item-name"><?php the_title();?></div>
            <div class="item-add " data-product_id="<?php echo get_the_ID();?>"><span>+</span></div>
        </div>
    </li>

<?php
    endwhile; 
    wp_reset_postdata();
?>