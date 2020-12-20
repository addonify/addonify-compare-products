<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    echo '<div class="checkbox-group">';
    echo '<label>';
    $this->checkbox($arg);
    echo esc_html( $arg['label'] ) .'</label>';
    echo '</div>';