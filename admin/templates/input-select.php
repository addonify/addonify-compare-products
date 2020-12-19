<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    echo '<select  name="'. esc_attr( $args['name'] ) .'" id="'. esc_attr( $args['name'] ) .'" >';

    echo '<pre>';
    var_dump( $options ); 
    echo '</pre>';

    foreach($options as $value => $label){

        echo '<option value="'. esc_attr( $value ) .'" ';

        if( $db_value == $value ) {
            echo 'selected';
        } 
        
        echo ' >' . esc_html( $label ) . '</option>';
    }
    
    echo '</select>';