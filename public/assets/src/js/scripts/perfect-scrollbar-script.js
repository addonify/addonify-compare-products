'use strict';

function AddonifyPerfectScrollBar(arg) {

    new PerfectScrollbar(arg, {
        wheelSpeed: 1,
        wheelPropagation: true,
        minScrollbarLength: 20,
        useBothWheelAxes: true,
    });
}

document.addEventListener("DOMContentLoaded", function () {

    var addonifyCompareScrollbarEle = document.querySelectorAll('.addonify-compare-scrollbar');

    addonifyCompareScrollbarEle.forEach((scrollbarEle) => {

        if ((scrollbarEle !== null) && (scrollbarEle !== undefined)) {

            AddonifyPerfectScrollBar(scrollbarEle);
        }
    })
});