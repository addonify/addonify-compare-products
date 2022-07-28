<?php

add_action( 'addonify_compare_products/compare_button', 'addonify_compare_products_render_compare_button' );

add_action( 'addonify_compare_products/docker_modal', 'addonify_compare_products_render_docker_modal' );

add_action( 'addonify_compare_products/docker_message', 'addonify_compare_products_render_docker_message' );
add_action( 'addonify_compare_products/docker_content', 'addonify_compare_products_render_docker_content' );
add_action( 'addonify_compare_products/docker_add_button', 'addonify_compare_products_render_docker_add_button' );
add_action( 'addonify_compare_products/docker_compare_button', 'addonify_compare_products_render_docker_compare_button' );

add_action( 'addonify_compare_products/search_modal', 'addonify_compare_products_render_search_modal' );
add_action( 'addonify_compare_products/search_result', 'addonify_compare_products_render_docker_search_result' );

add_action( 'addonify_compare_products/comparison_modal', 'addonify_compare_products_render_comparison_modal' );

add_action( 'addonify_compare_products/comparison_content', 'addonify_compare_products_render_comparison_content' );
