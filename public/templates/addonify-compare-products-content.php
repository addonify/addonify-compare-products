<?php defined( 'ABSPATH' ) || exit; ?>

<table id="addonofy-compare-products-table">
    <thead>
        <?php
            foreach($args['data'] as $key => $value){
                echo '<tr>';
                if($key == 'title'){
                    echo '<th></th>';
                    foreach($value as $data){
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
                    foreach($value as $data){
                        echo '<td>' . $data . '</td>';
                    }
                }
                echo '</tr>';
            }
        ?>
    </tbody>
</table>