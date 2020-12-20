<?php 
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;
?>

<div id="addonify-compare-products-table-wrapper" >

    <?php if( empty( $data ) ):?>
        <p><?php echo __( 'Nothing to compare !', 'addonify-compare-products' ); ?></p>
    <?php else: ?>
        
        <table id="addonify-compare-products-table">
            <thead>
                <tr>
                    <?php
                        foreach($data as $key => $value){
                            if($key == 'title'){
                                echo '<th>Title</th>';
                                foreach($value as $key1 => $value1){
                                    if ( $value1 ) {
                                        echo '<th>' . $value1 .  '</th>';
                                    }
                                }
                            }
                            break;
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ( $data as $key => $value ) {
                        
                        if ( 'title' !== $key ) {
                            echo '<tr>';
                            echo '<td class="acp-field-title">' . $key . '</td>';
                            foreach($value as $key1 => $value1) {
                                if ( $value1 ) {
                                    echo '<td  class="'. ( ( ! is_numeric( $key1 ) ? $key1 : '' ) ) .'" >' . $value1 . '</td>';
                                }
                            }
                            echo '</tr>';
                        }
                        
                    }
                ?>
            </tbody>
        </table>

    <?php endif;?>
    
</div>
