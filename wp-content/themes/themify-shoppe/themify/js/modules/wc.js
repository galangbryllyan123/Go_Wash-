(($,e,o,t,c,i,a)=>{"use strict";let r=!1,n=!1,l=o.querySelector("form.variations_form select"),s=[];const _=c.wc_version,f=c.wc_js["wc-add-to-cart-variation"],m=c.wc_js.wc_additional_variation_images_script,d=o.tfClass("woocommerce-ordering"),w=function(){this.closest("form").submit()},p=o.querySelectorAll("input.qty[min]"),u=e=>!e.includes("ver=",12)&&_,g=async o=>{if(c.photoswipe){o.preventDefault(),o.stopImmediatePropagation();const t=o.currentTarget;await Promise.all([e.loadCss(c.photoswipe.main,null,_),e.loadCss(c.photoswipe.skin,null,_)]),i((()=>{t.click()}),5)}},v=async(r,_,p,g)=>{const v=c.wc_js,h=r&&!0!==r,j=o=>{if(delete v[o],0===Object.keys(v).length){e.body.off("click.tf_wc_click").off("added_to_cart removed_from_cart",y);for(let e=0;e<d.length;++e)d[e].tfOff("change",w,{passive:!0,once:!0});i((()=>{if(r&&!0!==r&&"load"!==r)if("click"===r.type)for(let e=0;e<s.length;++e)s[e].isConnected&&s[e].click();else e.body.triggerHandler(r.type,[_,p,g]);s=null,e.trigger("tf_wc_js_load")}),5)}},k=async()=>{v.woocommerce&&await e.loadJs(v.woocommerce,$.scroll_to_notices!==a,u(v.woocommerce)),j("woocommerce")},b=async()=>{const i=o.tfClass("variations_form");if(i[0]||o.tfClass("wcpa_form_outer").length>0){m&&e.loadJs(m,$.wc_additional_variation_images_frontend!==a,u(m)),await e.loadJs(e.includesURL+"js/underscore.min",a!==t._,c.wp),await Promise.all([e.loadJs(e.includesURL+"js/wp-util.min",t.wp?.template!==a,c.wp),e.loadJs(f,$.fn.wc_variation_form!==a,u(f))]);for(let e=i.length-1;e>-1;--e)$(i[e]).wc_variation_form();l&&n&&e.triggerEvent(l,"change"),l=n=null}};!0===h&&(v["jquery-blockui"]&&e.loadJs(v["jquery-blockui"],$.blockUI===a,u(v["jquery-blockui"])).then((()=>{j("jquery-blockui")})),v["wc-add-to-cart"]?e.loadJs(v["wc-add-to-cart"],c.wc_js_normal!==a,u(v["wc-add-to-cart"])).then((()=>{j("wc-add-to-cart"),b()})):b(),v["wc-single-product"]&&!$.fn.wc_product_gallery&&"undefined"!=typeof wc_single_product_params?e.loadJs(v["wc-single-product"],$.fn.wc_product_gallery!==a,u(v["wc-single-product"])).then((()=>{j("wc-single-product"),e.trigger("tf_init_photoswipe")})):delete v["wc-single-product"]),v["js-cookie"]?(await e.loadJs(v["js-cookie"],!!t.Cookies,u(v["js-cookie"])),v["wc-cart-fragments"]&&await e.loadJs(v["wc-cart-fragments"],c.wc_js_normal!==a,u(v["wc-cart-fragments"])),j("js-cookie"),j("wc-cart-fragments"),!0===h&&k()):k()},y=(e,o,t,c)=>{!1===r&&(r=!0,v(e,o,t,$(c)))};if(e.body.one("added_to_cart removed_from_cart",y).on("click.tf_wc_click",".ajax_add_to_cart,.remove_from_cart_button",(e=>{e.preventDefault(),e.stopImmediatePropagation(),e.target.classList.contains("loading")||(s.push(e.target),e.target.classList.add("loading")),!1===r&&(r=!0,v(e))})),null!==l){const o=e=>{n||(t.tfOff("scroll pointermove",o,{once:!0,passive:!0}),l?.tfOff("change",o,{once:!0,passive:!0}),"change"===e?.type&&l&&e.stopImmediatePropagation(),!1===r&&(r=n=!0,v("load")))};t.tfOn("scroll pointermove",o,{once:!0,passive:!0}),l.tfOn("change",o,{once:!0,passive:!0}),i((()=>{e.requestIdleCallback(o,800)}),700)}for(let e=0;e<d.length;++e)d[e].tfOn("change",w,{passive:!0,once:!0});for(let e=p.length-1;e>-1;--e){let o=parseFloat(p[e].min);o>=0&&parseFloat(p[e].value)<o&&(p[e].value=o)}delete c.wc_js["wc-add-to-cart-variation"],delete c.wc_js.wc_additional_variation_images_script,e.on("tf_wc_init",(t=>{!0===t||null!==o.querySelector(".woocommerce-input-wrapper,.woocommerce-store-notice")?v("load"):v(!0),(async()=>{const t=o.tfClass("tf_wc_accordion")[0];t&&(await e.loadJs("wc-accordion-tabs"),e.trigger("tf_wc_acc_tabs_init",[t]))})(),e.trigger("tf_init_photoswipe")})).on("tf_init_photoswipe",(c=>{const r=t.wc_single_product_params;if($.fn.wc_product_gallery&&r!==a)if(r.photoswipe_enabled)for(let t=(c=c||o).tfClass("woocommerce-product-gallery"),n=t.length-1;n>-1;--n){let o=$(t[n]),c=t[n].tfClass("woocommerce-product-gallery__trigger")[0];if(!o.data("product_gallery")){o.wc_product_gallery(r);let e=o.data("flexslider");e&&i((()=>{e.resize()}),100)}c?.tfOn(e.click,g,{once:!0});let l=t[n].tfClass("woocommerce-product-gallery__image");$.fn.flexslider!==a&&r.flexslider_enabled&&l.length>1||1!==l.length||l[0].tfTag("a")[0]?.tfOn(e.click,g,{once:!0})}else e.off("tf_init_photoswipe")})).trigger("tf_init_photoswipe")})(jQuery,Themify,document,window,themify_vars,setTimeout,void 0);