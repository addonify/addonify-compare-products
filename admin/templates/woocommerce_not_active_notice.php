<?php

    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    echo '<div class="notice notice-error is-dismissible"><p>';
    _e( 'Addonify Compare Products is enabled but not effective. It requires WooCommerce in order to work.', 'addonify-compare-products' );
    echo '</p></div>';