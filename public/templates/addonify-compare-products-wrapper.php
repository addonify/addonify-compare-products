<?php
	defined( 'ABSPATH' ) || exit;
?>

<div id="addonify-compare-footer" class="hidden">
    <div id="addonify-compare-footer-message">Select atleast 2 items to compare</div>

    <div id="addonify-compare-footer-inner">

        <!-- add product button -->
        <div class="addonify-footer-components">
            <a href="#" id="addonify-footer-add" aria-label="Add product"></a>
        </div>

        <div id="addonify-footer-thumbnails"></div>
        <!--addonify-cbi-thumbnails-->


        <!-- compare button -->
        <div class="addonify-footer-components">
            <button id="addonify-footer-compare-btn">Compare</button>
        </div>
    </div>

</div>
<!--addonify-compare-products-footer-bar-->


<!-- search modal -->
<div id="addonify-compare-search-modal" class="hidden" >
    <div class="addonify-compare-search-model-inner">

        <button id="addonify-compare-search-close-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>


        <div class="addonify-compare-search-modal-content">

            <input type="text" name="query" value="" id="addonify-compare-search-query" placeholder="Search here">

            <div id="addonify-compare-search-results"></div>

        </div>

    </div>
</div>


<!-- product display modal -->
<div id="addonify-compare-modal" class="hidden" >
    <div class="addonify-compare-model-inner">

        <button id="addonify-compare-close-button" >
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div id="addonify-compare-modal-content" style="height: 100%; overflow: scroll;"></div>

    </div>
</div>