<?php
    defined( 'ABSPATH' ) || exit;
    
    $rows = $args['contents'];

    // $rows = [
    //     [
    //         'title'                     => 'this is product title',
    //         'url'                       => '#',
    //         'image'                     => 'http://localhost/multisite/wp-content/uploads/2020/07/t-shirt-with-logo-1-768x768.jpg',
    //         'description'               => 'This is a simple product.	',
    //         'sku'                       => 'Woo-tshirt-logo	',
    //         'price'                     => '£18.00	',
    //         'content'                   => 'Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.',
    //         'dimensions'                => 'N/A	',
    //         'additional_information'    => '',
    //         'add_to_cart'               => '',
    //     ]
    // ];
?>

<table id="addonofy-compare-products-table" >
    <thead>
        <tr>
            <?php do_action( 'addonify_compare_table_thead'); ?>
        </tr>
    </thead>
    <tbody>
        <?php 
            do_action( 'addonify_compare_table_product_image'); 
            do_action( 'addonify_compare_table_product_title'); 
            do_action( 'addonify_compare_table_product_rating'); 
            do_action( 'addonify_compare_table_product_price'); 
            do_action( 'addonify_compare_table_product_excerpt'); 
            do_action( 'addonify_compare_table_product_stock_info'); 
            do_action( 'addonify_compare_table_product_attributes'); 
            do_action( 'addonify_compare_table_product_add_to_cart_btn'); 
        ?>
    </tbody>
</table>