<?php

    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    printf(
        '<button type="button" class="addonify-cp-button button %s" data-product_id="%s" >%s</button>',
        esc_attr($args['css_class']),
        esc_attr( $args['product_id'] ),
        $args['label']
    );