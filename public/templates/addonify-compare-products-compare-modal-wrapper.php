<?php 
    // direct access is disabled
    defined( 'ABSPATH' ) || exit;
?>

<!-- product display modal -->
<div id="addonify-compare-modal" class="hidden" >
    <div class="addonify-compare-model-inner">

        <button id="addonify-compare-close-button" class="addonify-compare-all-close-btn" >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div id="addonify-compare-modal-content" style="height: 100%; overflow: scroll;"></div>

    </div>
</div>