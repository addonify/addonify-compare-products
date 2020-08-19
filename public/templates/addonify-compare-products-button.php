<?php

    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    printf(
        '<button type="button" class="addonify-cp-button button %s" data-product_id="%s" >%s</button>',
        esc_attr($data['css_class']),
        esc_attr( $data['product_id'] ),
        esc_attr($data['label'])
    );