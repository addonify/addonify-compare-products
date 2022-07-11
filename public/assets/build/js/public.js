function AddonifyPerfectScrollBar(o){new PerfectScrollbar(o,{wheelSpeed:1,wheelPropagation:!0,minScrollbarLength:20})}!function(I){"use strict";I(document).ready(function(){var o,a=I("body"),d=[],e=I("#addonify-footer-thumbnails"),n=I("#addonify-compare-footer-message"),i=I("#addonify-compare-search-modal"),t=I("#addonify-compare-search-results"),c=I("#addonify-compare-modal"),r=I("#addonify-compare-modal-content"),s=I("#addonify-footer-compare-btn"),f=!1,l=!1,p=!0,m=addonify_compare_ajax_object.display_type,u=addonify_compare_ajax_object.comparision_page_url,y=I("#addonify-compare-modal-overlay"),_=I("#addonify-compare-search-modal-overlay"),h=(I("#addonify-footer-thumbnails").sortable({stop:function(){d=[],e.find(".addonify-footer-components").each(function(){d.push(I(this).data("product_id"))}),f=!0,E(d),l&&(D(),f=!0,A())}}).disableSelection(),I("#addonify-compare-products-footer-bar a, button.addonify-cp-button").click(function(o){o.preventDefault()}),"addonify-overlay-btn-wrapper"),b="addonify-overlay-btn",v=I("."+h),j=I(".addonify-overlay-buttons"),j=(v.length?j.each(function(){var o=I("button."+b,this).clone();I("button."+b,this).remove(),I("."+h,this).append(o)}):(j.each(function(){I("button."+b,this).wrapAll('<div class=" '+h+' " />')}),v=I("img.attachment-woocommerce_thumbnail").height(),I("."+h).css("height",v+"px"),I("."+h).hover(function(){I(this).css("opacity",1)},function(){I(this).css("opacity",0)})),Cookies.get("addonify_compare_product_selected_product_ids"));j&&(d=I.map(j.split(","),function(o){return parseInt(o)})),C(),d.length&&((j=v=d.join(",")).split(",").forEach(function(o){I('button.addonify-cp-button[data-product_id="'+o+'"]').addClass("selected").attr("disabled",!0)}),k(v),x(v),g());function C(){1<d.length?s.fadeIn():s.fadeOut()}function g(){d.length?a.addClass("addonify-compare-footer-is-visible"):a.removeClass("addonify-compare-footer-is-visible")}function x(o){o.length&&(o={action:addonify_compare_ajax_object.action_get_thumbnails,ids:o},I.getJSON(addonify_compare_ajax_object.ajax_url,o,function(o){I.each(o,function(o,a){I('.addonify-footer-thumbnail[data-product_id="'+o+'"]').append('<img src="'+a+'">').removeClass("loading")})}))}function k(o){var o=o.split(","),a="";o.forEach(function(o){a+='<div class="addonify-footer-components" data-product_id="'+o+'"><div class="sortable addonify-footer-thumbnail loading" data-product_id="'+o+'"><span class="addonify-footer-remove addonify-footer-btn" data-product_id="'+o+'"></span></div></div>'}),e.append(a)}function w(){d.length<2?n.removeClass("addonify-compare-hidden"):n.addClass("addonify-compare-hidden")}function S(){i.addClass("addonify-compare-hidden"),t.html(""),I("#addonify-compare-search-query").val("")}function A(){var o;y.removeClass("addonify-compare-hidden"),a.removeClass("addonify-compare-footer-is-visible"),f&&r.html(""),l=!0,c.removeClass("addonify-compare-hidden"),r.addClass("loading"),(o=d.join(","))&&f&&(o={action:addonify_compare_ajax_object.action_get_compare_contents},I.get(addonify_compare_ajax_object.ajax_url,o,function(o){r.removeClass("loading").html(o)}))}function D(){y.addClass("addonify-compare-hidden"),a.addClass("addonify-compare-footer-is-visible"),c.addClass("addonify-compare-hidden"),f=l=!1}function E(o){o=o.join(","),"browser"==addonify_compare_ajax_object.cookie_expire?Cookies.set("addonify_compare_product_selected_product_ids",o,{path:addonify_compare_ajax_object.cookie_path,domain:addonify_compare_ajax_object.cookie_domain}):Cookies.set("addonify_compare_product_selected_product_ids",o,{path:addonify_compare_ajax_object.cookie_path,domain:addonify_compare_ajax_object.cookie_domain,expires:parseInt(addonify_compare_ajax_object.cookie_expire)})}w(),a.on("click","button.addonify-cp-button, #addonify-compare-search-results .item-add",function(o){o.preventDefault();o=I(this).data("product_id");o&&(S(),d.includes(o)?f=!1:(I(this).attr("disabled",!0),f=!0,d.push(o),E(d),I(this).addClass("selected"),i.addClass("addonify-compare-hidden"),k(o.toString()),x(o.toString()),a.addClass("addonify-compare-footer-is-visible"),w()),g(),C(),p=p&&!(f=!0))}),a.on("click",".addonify-footer-remove",function(o){o.preventDefault();var a=I(this).data("product_id");if(a){f=!0,I('button.addonify-cp-button[data-product_id="'+a+'"]').removeClass("selected").removeAttr("disabled");for(var e=0;e<d.length;e++)d[e]==a&&d.splice(e,1);E(d),I('.addonify-footer-components[data-product_id="'+a+'"]').remove(),g(),C(),w(),l&&(D(),f=!0,A())}}),a.on("click","#addonify-footer-add",function(){a.removeClass("addonify-compare-footer-is-visible"),_.removeClass("addonify-compare-hidden"),i.removeClass("addonify-compare-hidden")}),a.on("click","#addonify-compare-search-close-button, #addonify-compare-search-modal-overlay",function(){a.addClass("addonify-compare-footer-is-visible"),_.addClass("addonify-compare-hidden"),S()}),a.on("keyup","#addonify-compare-search-query",function(){var a=I(this).val();clearTimeout(o),o=setTimeout(function(){var o;(o=a).length&&(t.html("").addClass("loading"),o={action:addonify_compare_ajax_object.action_search_items,query:o,nonce:addonify_compare_ajax_object.nonce},I.post(addonify_compare_ajax_object.ajax_url,o,function(o){t.removeClass("loading").html(o)}))},500)}),a.on("click","#addonify-footer-compare-btn",function(){a.addClass("addonify-compare-modal-is-visible"),"page"==m?window.location.href=u:l?D():(p=p&&!(f=!0),A())}),a.on("click","#addonify-compare-close-button, #addonify-compare-modal-overlay",function(){D(),a.removeClass("addonify-compare-modal-is-visible")})})}(jQuery),document.addEventListener("DOMContentLoaded",function(){var o=document.getElementById("addonify-compare-modal-content");null!=o&&AddonifyPerfectScrollBar(o)});