<?php defined( 'ABSPATH' ) || exit; ?>

<table id="addonofy-compare-products-table">
    <thead>
        <?php
            foreach($args['data'] as $key => $value){
                echo '<tr>';
                if($key == 'title'){
                    echo '<th></th>';
                    foreach($value as $key1 => $data){
                        echo '<th>' . $data . '</th>';
                    }
                }
                echo '</tr>';
                break;
            }
        ?>
    </thead>
    <tbody>
        <?php
            foreach($args['data'] as $key => $value){
                echo '<tr>';
                if($key != 'title'){
                    echo '<td>' . $key . '</td>';
                    foreach($value as $key1 => $data){
                        echo '<td  class="'. ( ( ! is_numeric( $key1 ) ? $key1 : '' ) ) .'" >' . $data . '</td>';
                    }
                }
                echo '</tr>';
            }
        ?>
    </tbody>
</table>