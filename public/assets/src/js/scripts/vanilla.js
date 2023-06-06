'use strict';

/**
 * 
 * Init perfect scrollbar.
 * 
 * @return {void} void
 */
const initAddonifyCompareProductsScrollBar = () => {

    if (typeof PerfectScrollbar === 'function') {

        let addonifyCompareScrollbarEle = document.querySelectorAll('.addonify-compare-scrollbar');

        addonifyCompareScrollbarEle.forEach((scrollEle) => {

            new PerfectScrollbar(scrollEle, {
                wheelSpeed: 1,
                wheelPropagation: true,
                minScrollbarLength: 10,
                useBothWheelAxes: true,
            });
        });

    } else {

        console.warn("Info: Addonify Compare Products, PerfectScrollbar is not defined. Perfect scroll bar won't be initialized.");
    }
}


/**
*
* Calculate the height of the model content.
*
* @return {void} void
* @since 1.1.7
*/
const addonifyCompareProductsModelCalcHeight = () => {

    const height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    const addonifyCompareModelEle = document.getElementById('addonify-compare-modal-content');

    if (addonifyCompareModelEle) {

        let processHeight = height - 40; // Deduct '40px' from the height of the window.
        addonifyCompareModelEle.style.height = processHeight + 'px';
    }
}

/**
*
* DOMContentLoaded event.
*
* @since 1.1.7
*/

document.addEventListener('DOMContentLoaded', function (e) {

    addonifyCompareProductsModelCalcHeight();
    initAddonifyCompareProductsScrollBar();
});

/**
*
* Window resize event.
*
* @since 1.1.7
*/

window.addEventListener('resize', function (e) {

    addonifyCompareProductsModelCalcHeight();
});