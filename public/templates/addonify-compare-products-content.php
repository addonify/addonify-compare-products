<?php defined( 'ABSPATH' ) || exit; ?>

<div id="addonofy-compare-products-table-wrapper" >

    <?php if( empty( $data['data'] ) ):?>
        <p>Nothing to compare !</p>
    <?php else:?>

        <table id="addonofy-compare-products-table">
            <thead>
                <?php
                    foreach($data['data'] as $key => $value){
                        echo '<tr>';
                        if($key == 'title'){
                            echo '<th></th>';
                            foreach($value as $key1 => $value1){
                                echo '<th>' . $value1 . '</th>';
                            }
                        }
                        echo '</tr>';
                        break;
                    }
                ?>
            </thead>
            <tbody>
                <?php
                    foreach($data['data'] as $key => $value){
                        echo '<tr>';
                        if($key != 'title'){
                            echo '<td>' . $key . '</td>';
                            foreach($value as $key1 => $value1){
                                echo '<td  class="'. ( ( ! is_numeric( $key1 ) ? $key1 : '' ) ) .'" >' . $value1 . '</td>';
                            }
                        }
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>

    <?php endif;?>
    
</div>
