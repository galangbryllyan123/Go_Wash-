((t,e,i,o)=>{"use strict";let l,n,r=null;const f=i.createElement("div"),s=e=>{requestAnimationFrame((()=>{let i=e.clientX,o=e.clientY;l===i&&n===o||(l=i,n=o,f.style.left=i+"px",f.style.top=o+"px",f.classList.toggle("left",i>t.w/2),f.classList.toggle("top",o>t.h/2))}))},m=(e,o)=>{o.tfOn("pointerenter",(function(l){!0===r&&(e.classList.remove("tf_hide"),s(l),this.tfOn("pointermove",s,{passive:!0}),(t.isTouch?i.body:this).tfOn("pointerleave",(()=>{o.tfOff("pointermove",s),e.classList.add("tf_hide")}),{once:!0,passive:!0}))}),{passive:!0})},p=()=>{null===r&&t.loadCss("tooltip").then((()=>{r=!0}));const e=o.builder_tooltips,l=i.createDocumentFragment();if(e)for(let t in e){let o=i.tfClass("themify_builder_content-"+t),n=e[t];for(let t=o.length-1;t>-1;--t)for(let e in n){const r=o[t].tfClass("tb_"+e);if(!(r.length<1))for(let t=r.length-1;t>-1;--t){let o=i.createElement("div"),f=r[t].classList,s=1;f.contains("module_row")?s=5:f.contains("tb-column")?s=4:f.contains("module_subrow")?s=3:f.contains("sub_column")&&(s=2),o.className="tf_tooltip tf_hide order-"+s,n[e].c&&(o.style.color=n[e].c),n[e].bg&&(o.style.backgroundColor=n[e].bg),n[e].w&&(o.style.width=n[e].w),o.textContent=n[e].t,m(o,r[t]),l.appendChild(o)}}}const n=o.menu_tooltips;if(n?.length>0)for(let t=n.length-1;t>-1;--t){let e=i.querySelector(n[t]);if(e)for(let t=e.querySelectorAll(".menu-item a[title]"),o=t.length-1;o>-1;--o){let e=i.createElement("div");e.className="tf_tooltip tf_hide",e.textContent=t[o].title||"",m(e,t[o]),t[o].removeAttribute("title"),l.appendChild(e)}}f.className="tf_tooltip_wrap",f.appendChild(l),requestAnimationFrame((()=>{i.body.appendChild(f)}))};!0===e.loaded?t.requestIdleCallback(p,200):e.tfOn("load",p,{once:!0,passive:!0})})(Themify,window,document,themify_vars);