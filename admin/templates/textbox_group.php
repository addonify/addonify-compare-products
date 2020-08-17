<?php
    
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;

    echo '<div class="textbox-group">';
    printf(
        '<label class="label">%1$s</label> <input type="text" class="%4$s" name="%2$s" id="%2$s" value="%3$s" /> %5$s',
        $args['label'],
        $args['name'],
        $db_value,
        $css_class,
        $extra_attr
    );
    echo '</div>';