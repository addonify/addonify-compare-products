<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    echo '<div class="checkbox-group">';
    $this->checkbox($arg);
    echo '<label>'.  esc_html( $arg['label'] ) .'</label>';
    echo '</div>';