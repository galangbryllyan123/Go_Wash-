(($,t,e,l)=>{"use strict";let i=!1;const d=t.is_builder_active,n=(t,e,l)=>{let i=t.getAttribute("data-"+e+"-"+l);if(!i&&"desktop"!==e){const d=["mobile","tablet","tablet_landscape","desktop"];for(let n=d.indexOf(e)+1;n<4;++n)if(i=t.getAttribute("data-"+d[n]+"-"+l),i){t.setAttribute("data-"+e+"-"+l,i);break}}return i?.split(",")||[]},o=(o,r)=>{const a=t.body,f=a.outerWidth(),_=a.offset().left;if(0===f)return;const u=themify_vars.breakpoints,c=e.documentElement.clientWidth,m=e.createDocumentFragment();let p="desktop";c<=u.mobile?p="mobile":c<=u.tablet[1]?p="tablet":c<=u.tablet_landscape[1]&&(p="tablet_landscape");for(let t=o.length-1;t>-1;--t){if(!r&&!d&&!1===i){if(o[t].hasAttribute("data-fullwidth-done"))continue;o[t].dataset.fullwidthDone=1}let a=o[t].closest(".themify_builder_content");if(null===a||null!==a.closest(".slide-content"))continue;let u,c=$(a).offset().left-_,h=f-c-a.offsetWidth,s="";if(d)(a.classList.contains("tb_zooming_50")||a.classList.contains("tb_zooming_75"))&&(c=h=0);else{let e=o[t].dataset.css_id;if(e)e="tb_"+e;else{let l=o[t].className.match(/module_row_(\d+)/gi)?.[0];if(!l)continue;e=l.trim()}u="tb-fulllwidth-"+e,s+=".themify_builder.themify_builder_content>."+e+".module_row{"}if(o[t].classList.contains("fullwidth")){let e=n(o[t],p,"margin"),i="";e[0]?(i=e[0],s+="margin-left:calc("+e[0]+" - "+l.abs(c)+"px);"):s+="margin-left:"+-c+"px;",e[1]?(""!==i&&(i+=" + "),i+=e[1],s+="margin-right:calc("+e[1]+" - "+l.abs(h)+"px);"):s+="margin-right:"+-h+"px;",s+=""!==i?"width:calc("+f+"px - ("+i+"));":"width:"+f+"px;"}else if(s+="margin-left:"+-c+"px;margin-right:"+-h+"px;width:"+f+"px;",c||h){let e=n(o[t],p,"padding"),i="+";c&&(e[0]?(c<0&&(i="-"),s+="padding-left:calc("+e[0]+" "+i+" "+l.abs(c)+"px);"):s+="padding-left:"+l.abs(c)+"px;"),h&&(e[1]?(i=h>0?"+":"-",s+="padding-right:calc("+e[1]+" "+i+" "+l.abs(h)+"px);"):s+="padding-right:"+l.abs(h)+"px;")}if(d){let e=o[t].style;e.paddingRight=e.paddingLeft=e.marginRight=e.marginLeft="",e.cssText+=s}else{s+="}",e.tfId(u)?.remove();let t=e.createElement("style");t.setAttribute("id",u),t.textContent=s,m.appendChild(t)}}d||(e.head.appendChild(m),!0!==r&&t.trigger("tfsmartresize",{w:t.w,h:t.h}))};t.on("builder_load_module_partial",((e,l)=>{let i;if(!0===l){const t=e.classList;if(!t.contains("fullwidth")&&!t.contains("fullwidth_row_container")||e.closest(".tb_overlay_content_lp"))return;i=[e]}else i=t.selectWithParent(".fullwidth.module_row,.fullwidth_row_container.module_row",e);i.length>0&&o(i,!1)})).on("tfsmartresize",(()=>{!1===i&&(i=!0,o(e.querySelectorAll(".fullwidth.module_row,.fullwidth_row_container.module_row")),i=!1)}))})(jQuery,Themify,document,Math);