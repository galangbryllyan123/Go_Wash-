((t,e,i)=>{"use strict";const s=()=>i.all([t.loadCss(themifyScript.wc_css_url+"slider","theme_slider_css",themify_vars.theme_v,e.tfId("themify_concate-css")?.nextElementSibling),t.carousel("load")]);t.on("themify_theme_product_slider",(_=>{const f=_.closest(".products"),a=_.dataset,r=a.link,{width:o=a.w,height:d=a.h}=f.dataset,l=e.createElement("span");l.className="tf_loader",_.appendChild(l);const h=t.fetch({action:"themify_product_slider",slider:a.productSlider,width:o,height:d},null,{credentials:"omit"});i.all([h,s()]).then((s=>{if(s=s[0]){const f=e.createDocumentFragment(),a=e.createDocumentFragment(),o=e.createElement("div"),d=e.createElement("div"),h=e.createElement("div"),c=e.createElement("div"),n=e.createElement("div"),w=s.big,p=w.length,m=[];if(p>0){for(let t=0;t<p;++t){let i=e.createElement("div"),_=e.createElement("div"),o=new Image,d=new Image;i.className=_.className="tf_swiper-slide tf_box",o.src=w[t],m.push(o.decode().catch((()=>{})).finally((()=>{if(r){let t=e.createElement("a");t.href=r,t.appendChild(o),i.appendChild(t)}else i.appendChild(o)}))),p>1&&(d.src=s.thumbs[t],d.width=d.height=28,m.push(d.decode().catch((()=>{})).finally((()=>{_.appendChild(d)}))),a.appendChild(_)),f.appendChild(i)}o.className="tf_sw_slider_wrap tf_abs tf_w tf_h tf_opacity tf_hidden",h.className=d.className="tf_swiper-container tf_carousel",d.className+=" tf_sw_main tf_h tf_overflow",h.className+=" tf_sw_thumbs_wrap tf_w tf_abs_b",c.className=n.className="tf_swiper-wrapper tf_rel tf_w tf_h",n.className+=" tf_box",p<8&&(n.className+=" tf_sw_thumbs_center"),c.appendChild(f),d.appendChild(c),o.appendChild(d),p>1&&(n.appendChild(a),h.appendChild(n),o.appendChild(h)),i.allSettled(m).then((()=>{const e=()=>{_.classList.add("slider-finish"),o.classList.remove("tf_opacity","tf_hidden"),l.remove()};if(_.appendChild(o),p>1){d.tfOn("tf_swiper_init",e,{passive:!0,once:!0}),d.dataset.thumbs=h.dataset.thumbsId=Math.random().toString(36).substr(2,9);const i=p>=8?8:"auto";t.carousel(h,{visible:i,tabVisible:i,mobVisible:i,allowTouchMove:8===i,lazy:!1,slider_nav:!1,pager:!1,wrapvar:!1,height:"auto"}),t.carousel(d,{pager:!1,lazy:!1,wrapvar:!0,height:"auto",auto:2500,speed:1500})}else d.classList.add("tf_sw_init"),e()}))}else l.remove()}}))}))})(Themify,document,Promise);