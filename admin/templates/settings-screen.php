<?php 
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;
?>

<div class="wrap">

    <h1><?php _e( 'Compare Products Options', 'addonify-quick-view' );?></h1>

    <div id="addonify-settings-wrapper">
            
        <ul id="addonify-settings-tabs">
            <li>
                <a href="<?php echo $tab_url;?>settings" class="<?php if( $current_tab == 'settings') echo 'active';?> " > 
                    <?php echo translate( 'Settings' );?> 
                </a>
            </li>
            <li>
                <a href="<?php echo $tab_url;?>styles" class="<?php if( $current_tab == 'styles') echo 'active';?> " > 
                    <?php echo translate( 'Styles' );?> 
                </a>
            </li>
        </ul>

        <?php if( $current_tab == 'settings' ):?>

            <!-- settings tabs -->
            <form method="POST" action="options.php">
            
                <!-- generate nonce -->
                <?php settings_fields("compare_products_settings"); ?>

                <div id="addonify-settings-container" class="addonify-content">
                    <!-- display form fields -->

                    <div id="addonify-settings-container" class="addonify-section ">
                        <?php do_settings_sections($this->settings_page_slug.'-settings'); ?>
                    </div>

                    <div id="addonify-settings-options-container" class="addonify-section">
                        <?php do_settings_sections($this->settings_page_slug.'-settings-table-options'); ?>
                    </div>

                </div><!--addonify-settings-container-->

                <?php submit_button(); ?>

            </form>
        
        <?php elseif( $current_tab == 'styles'):?>

            <!-- styles tabs -->
            <form method="POST" action="options.php">
            
                <!-- generate nonce -->
                <?php settings_fields("compare_products_styles"); ?>

                <div id="addonify-styles-container" class="addonify-content">

                    <div id="addonify-style-options-container" class="addonify-section ">
                        <?php do_settings_sections($this->settings_page_slug.'-styles'); ?>
                    </div>

                    <div id="addonify-content-colors-container" class="addonify-section">
                        <?php do_settings_sections($this->settings_page_slug.'-content-colors'); ?>
                    </div>
                </div><!--addonify-styles-container-->

                <?php submit_button(); ?>

            </form>

        <?php endif;?>
    
    </div><!--addonify-settings-wrapper-->
    
</div> <!--wrap-->