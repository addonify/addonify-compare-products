<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    echo '<div class="colorpicker-group">';
    
    if( isset($arg['label']) ) {
        printf(
            '<p>%s</p>',
            esc_html( $arg['label'] )
        );
    }

    printf(
        '<input type="text" value="%2$s" name="%1$s" id="%1$s" class="color-picker" data-alpha="true" />',
        esc_attr( $arg['name'] ),
        esc_attr( $db_value )
    );

    echo '</div>';