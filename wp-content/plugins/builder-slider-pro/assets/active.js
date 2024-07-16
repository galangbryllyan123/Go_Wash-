((e,t,l,s)=>{"use strict";t.ModuleProSlider=class extends t.Module{constructor(t){const l=t.mod_settings||{},s=l.builder_slider_pro_slides;if(!l.c&&s){for(let t=s.length-1;t>-1;--t){let l=s[t],i=l.builder_ps_vbg_option;if(i){e.parseVideo(i).type?(l.controls="1",i.includes("autoplay=")&&(l.autoplay="1",l.builder_ps_vbg_option=e.updateQueryString("autoplay",null,i))):l.autoplay="1"}}l.builder_slider_pro_slides=s}super(t)}static getOptions(){const e={shortTop:"slp_ftop",shortBottom:"slp_fbtm",shortLeft:"slp_fleft",shortRight:"slp_frght",disable:"dis"},t={shortTopOut:"slp_ttop",shortBottomOut:"slp_tbtm",shortLeftOut:"slp_tleft",shortRightOut:"slp_trght"};return[{id:"mod_title_slider",type:"title"},{id:"builder_slider_pro_slides",type:"builder",options:[{id:"builder_ps_layout",type:"layout",mode:"sprite",label:"slp_sldlay",default:"bsp-slide-content-left",options:[{img:"image_left",value:"bsp-slide-content-left",label:"slp_txtrght"},{img:"image_center",value:"bsp-slide-content-center",label:"slp_txtcen"},{img:"image_right",value:"bsp-slide-content-right",label:"slp_txtlft"}]},{id:"builder_ps_slide_type",type:"select",label:"slp_bgtype",options:{Image:"image",Video:"vid"},option_js:!0,binding:{Image:{show:"tb_group_element_Image",hide:"tb_group_element_Video"},Video:{show:"tb_group_element_Video",hide:"tb_group_element_Image"}}},{type:"multi",label:"slp_sldbg",options:[{id:"builder-ps-bg-color",type:"color"},{id:"builder-ps-bg-image",type:"image",class:"large"}]},{id:"builder_ps_vbg_option",type:"video",label:"vidurl",help:"vidurlh",wrap_class:"tb_group_element_Video"},{id:"autoplay",type:"toggle_switch",label:"autoplay",wrap_class:"tb_group_element_Video",options:{on:{name:"1",value:"y"},off:{name:"",value:"no"}}},{id:"controls",type:"toggle_switch",label:"ctrols",wrap_class:"tb_group_element_Video",options:{on:{name:"1",value:"s"},off:{name:"",value:"hi"}}},{id:"builder-ps-slide-image",type:"image",label:"slp_sldimg",help:"slp_sldimgh"},{type:"multi",label:"imgs",options:[{id:"s_c_w",type:"number",label:"w",min:1,after:"px"},{id:"s_c_h",type:"number",label:"ht",after:"px"}]},{id:"builder_ps_heading",type:"text",label:"slp_sldhd",control:{selector:'[data-name="builder_ps_heading"]',rep:".sp-slide"}},{id:"builder_ps_text",type:"wp_editor",control:{selector:'[data-name="builder_ps_text"]',rep:".sp-slide"}},{type:"multi",label:"slp_sldtxt",options:[{id:"builder_ps_text_color",type:"color",label:"c"},{id:"builder_ps_text_link_color",type:"color",label:"slp_lclr"}]},{type:"group",display:"accordion",label:"slp_btn",options:[{id:"builder_ps_button_text",type:"text",label:"btntext",control:{selector:'[data-name="builder_ps_button_text"]',rep:".sp-slide"},binding:{empty:{hide:"bsp_btn_options"},not_empty:{show:"bsp_btn_options"}}},{type:"group",wrap_class:"bsp_btn_options",options:[{id:"builder_ps_button_action_type",type:"select",label:"slp_btnact",options:{custom:"slp_gotol",next_slide:"slp_nsld",prev_slide:"slp_psld",lightbox:"lg"},binding:{custom:{show:["builder_ps_button_link","builder_ps_new_tab"],hide:"tb_group_element_lightbox"},lightbox:{show:["builder_ps_button_link","tb_group_element_lightbox"]},select:{values:["next_slide","prev_slide"],hide:["builder_ps_button_link","tb_group_element_lightbox","builder_ps_new_tab"]}}},{id:"builder_ps_button_link",type:"url",label:"l"},{id:"builder_ps_new_tab",type:"toggle_switch",label:"olnknt",options:"simple"},{type:"multi",label:"lbdim",options:[{id:"l_w",type:"range",label:"w",control:!1,units:{px:{max:3e3},"%":""}},{id:"l_h",label:"ht",control:!1,type:"range",units:{px:{max:3e3},"%":""}}],wrap_class:"tb_group_element_lightbox"},{type:"multi",label:"slp_btn",options:[{id:"builder_ps_button_icon",type:"icon",label:"icon"},{id:"builder_ps_button_color",type:"color",label:"c"},{id:"builder_ps_button_bg",type:"color",label:"bg"}]}]}]},{type:"group",display:"accordion",label:"slp_trn",options:[{type:"multi",label:"slp_sldtrn",options:[{id:"builder_ps_tranzition",type:"select",options:{slideTop:"slup",slideBottom:"slp_slideBottom",slideLeft:"sllft",slideRight:"slrgt",slideTopFade:"slp_slideTopFade",slideBottomFade:"slp_slideBottomFade",slideLeftFade:"slp_slideLeftFade",slideRightFade:"slp_slideRightFade",fade:"fade",zoomOut:"zoom",zoomTop:"slp_zoomTop",zoomBottom:"slp_zoomBottom",zoomLeft:"slp_zoomLeft",zoomRight:"slp_zoomRight"}},{id:"builder_ps_tranzition_duration",type:"select",options:{fast:"fast",normal:"n",slow:"slow"},default:"normal"}]},{type:"multi",label:"slp_sldtitle",options:[{id:"builder_ps_h3s_timer",type:"select",tooltip:"slp_sanim",options:e,binding:{disable:{hide:"builder_ps_h3e_timer"},not_empty:{show:"builder_ps_h3e_timer"}}},{id:"builder_ps_h3e_timer",type:"select",tooltip:"slp_eanim",options:t}]},{type:"multi",label:"slp_sldtxt",options:[{id:"builder_ps_ps_timer",type:"select",tooltip:"slp_sanim",options:e,binding:{disable:{hide:"builder_ps_pe_timer"},not_empty:{show:"builder_ps_pe_timer"}}},{id:"builder_ps_pe_timer",type:"select",tooltip:"slp_eanim",options:t}]},{type:"multi",label:"slp_actbtn",options:[{id:"builder_ps_as_timer",type:"select",tooltip:"slp_sanim",options:e,binding:{disable:{hide:"builder_ps_ae_timer"},not_empty:{show:"builder_ps_ae_timer"}}},{id:"builder_ps_ae_timer",type:"select",tooltip:"slp_eanim",options:t}]},{type:"multi",label:"slp_sldimg",options:[{id:"builder_ps_imgs_timer",type:"select",tooltip:"slp_sanim",options:e,binding:{disable:{hide:"builder_ps_imge_timer"},not_empty:{show:"builder_ps_imge_timer"}}},{id:"builder_ps_imge_timer",type:"select",tooltip:"slp_eanim",options:t}]}]}]},{type:"group",label:"sl_opt",display:"accordion",options:[{id:"builder_ps_triggers_position",type:"radio",label:"pager",wrap_class:"tb_compact_radios",options:[{value:"standard",name:"slp_def_ov"},{value:"below",name:"below"},{value:"none",name:"dis"}],binding:{none:{hide:"bsp_pager_wrap"},select:{value:["standard","below"],show:"bsp_pager_wrap"}}},{id:"builder_ps_wrap",label:"loop",type:"toggle_switch",options:"simple"},{type:"group",wrap_class:"bsp_pager_wrap",options:[{id:"builder_ps_triggers_type",type:"radio",label:"slp_pgrdes",options:[{value:"circle",name:"circle"},{value:"thumb",name:"slp_photmb"},{value:"square",name:"square"}],wrap_class:"tb_compact_radios",option_js:!0},{type:"multi",label:"slp_thmbsize",options:[{id:"builder_ps_thumb_width",type:"number",label:"thmbw",after:"px",default:30,class:"medium"},{id:"builder_ps_thumb_height",type:"number",label:"thmbh",after:"px",default:30,class:"medium"}],wrap_class:"tb_group_element_thumb"}]},{id:"builder_ps_aa",type:"select",label:"autoplay",options:{2e3:"2s",3e3:"3s",4e3:"4s",5e3:"5s",6e3:"6s",7e3:"7s",8e3:"8s",9e3:"9s",1e4:"10s",11e3:"slp_11s",12e3:"slp_12s",13e3:"slp_13s",14e3:"slp_14s",15e3:"15s",off:"off"},default:"off",binding:{off:{hide:"bsp_autoplay_opt"},select:{value:[2e3,3e3,4e3,5e3,6e3,7e3,8e3,9e3,1e4,11e3,12e3,13e3,14e3,15e3],show:"bsp_autoplay_opt"}}},{type:"group",wrap_class:"bsp_autoplay_opt",options:[{id:"builder_ps_hover_pause",type:"select",label:"slp_onhvr",options:{none:"slp_autoplay_c",pause:"slp_autoplay_p",stop:"slp_autoplay_s"},default:"pause"},{id:"builder_ps_timer",type:"checkbox",label:"",options:[{name:"yes",value:"slp_s_timer"}]}]},{type:"multi",label:"slp_slds",wrap_class:"tb_checkbox_element tb_checkbox_element_fullscreen",options:[{id:"builder_ps_width",type:"number",label:"w",after:"px"},{id:"builder_ps_height",type:"number",label:"ht",after:"px"}],help:"slp_sldsh"},{id:"builder_ps_fullscreen",type:"checkbox",label:"",options:[{name:"fullscreen",value:"slp_en_fs_sld"}],option_js:!0,reverse:!0},{id:"touch_swipe_desktop",type:"toggle_switch",label:"slp_dskswipe",options:{on:{name:"yes",value:"en"},off:{name:"no",value:"dis"}},default:"on",help:"slp_dskswipeh"},{id:"touch_swipe_mob",type:"toggle_switch",label:"slp_mblswipe",options:{on:{name:"yes",value:"en"},off:{name:"no",value:"dis"}},default:"on",help:"slp_mblswipeh"}]},{type:"custom_css_id",custom_css:"css_slider_pro"},{type:"hidden",id:"c",default:"1"}]}static getAnimation(){return!1}static getGroup(){return["addon"]}static default(){const e=themifyBuilder.i18n.label,t=s.url+"sample/";return{builder_slider_pro_slides:[{builder_ps_layout:"bsp-slide-content-right",builder_ps_text_color:"ffffff_1","builder-ps-bg-image":t+"slider-pro-bg-image.jpg","builder-ps-slide-image":t+"slider-pro-content-image.png",builder_ps_heading:e.slp_sldhd,builder_ps_text:e.slp_sldtxt}]}}static builderSave(e){const t={builder_ps_triggers_position:"standard",builder_ps_wrap:"no",builder_ps_triggers_type:"circle",builder_ps_thumb_width:"30",builder_ps_thumb_height:"30",builder_ps_aa:"off",touch_swipe_mob:"yes",touch_swipe_desktop:"yes",builder_ps_hover_pause:"pause",builder_ps_timer:!1,builder_ps_fullscreen:!1},l={builder_ps_layout:"bsp-slide-content-left",builder_ps_slide_type:"Image",builder_ps_button_action_type:"custom",builder_ps_new_tab:"no",builder_ps_tranzition:"slideTop",builder_ps_tranzition_duration:"normal",builder_ps_h3s_timer:"shortTop",builder_ps_h3e_timer:"shortTopOut",builder_ps_ps_timer:"shortTop",builder_ps_pe_timer:"shortTopOut",builder_ps_as_timer:"shortTop",builder_ps_ae_timer:"shortTopOut",builder_ps_imgs_timer:"shortTop",builder_ps_imge_timer:"shortTopOut"},s=e.builder_slider_pro_slides||[];delete e.pause_last_slide;for(let l in t)e[l]===t[l]&&delete e[l];"none"===e.builder_ps_triggers_position&&delete e.builder_ps_triggers_type,"thumb"!==e.builder_ps_triggers_type?(delete e.builder_ps_thumb_width,delete e.builder_ps_thumb_height):("30"===e.builder_ps_thumb_width?.toString()&&delete e.builder_ps_thumb_width,"30"===e.builder_ps_thumb_height?.toString()&&delete e.builder_ps_thumb_height),e.builder_ps_aa?"|"!==e.builder_ps_timer&&"false"!==e.builder_ps_timer||delete e.builder_ps_timer:(delete e.builder_ps_timer,delete e.builder_ps_hover_pause),"|"!==e.builder_ps_fullscreen&&"false"!==e.builder_ps_fullscreen||delete e.builder_ps_fullscreen;for(let e=s.length-1;e>-1;--e){let t=s[e],i=t.builder_ps_button_action_type;for(let e in l)t[e]===l[e]&&delete t[e];"Video"!==t.builder_ps_slide_type&&delete t.builder_ps_vbg_option,t.builder_ps_vbg_option||(delete t.autoplay,delete t.controls);for(let e=["h3","p","a","img"],l=e.length-1;l>-1;--l){let s=e[l];"disable"===t["builder_ps_"+s+"s_timer"]&&delete t["builder_ps_"+s+"e_timer"]}t.builder_ps_button_text&&"next_slide"!==i&&"prev_slide"!==i||(delete t.builder_ps_button_link,t.builder_ps_button_text||(i=null,delete t.builder_ps_button_action_type)),t.builder_ps_button_link&&"lightbox"!==i||delete t.builder_ps_new_tab,t.builder_ps_button_link&&"lightbox"===i||(delete t.l_w,delete t.l_h),t.l_w||delete t.l_w_unit,t.l_h||delete t.l_h_unit,t.builder_ps_button_link||"next_slide"===i||"prev_slide"===i||(delete t.builder_ps_button_icon,delete t.builder_ps_button_color,delete t.builder_ps_button_bg)}super.builderSave(e)}inlineEditorStart(){const e=TfSliderPro.getInstance(this.el.tfClass("slider-pro")[0]),t=e?.autoplay;e&&(e.swipeDisable=!0,t&&(t.stop(),t.disable=!0))}inlineEditorEnd(){const e=TfSliderPro.getInstance(this.el.tfClass("slider-pro")[0]),t=e?.autoplay;t?.disable&&(t.disable=null,t.start()),e?.swipeDisable&&(e.swipeDisable=null)}inlineUpdateModule(){return!1}imageEditStart(e,t){if(e?.classList.contains("sp-thumbnail")){const t=e.parentNode.style;t.width=t.height="auto"}}imageEditResize(e,t,l,s){this.imageEditEnd(e,t,l,s)}imageEditEnd(e,t,l,s){if(e?.classList.contains("sp-thumbnail")){const t=e.closest(".sp-buttons");t.style.height=s+"px";for(let e=t.children,s=e.length-1;s>-1;--s){let t=e[s].style;t.width=l+"px",t.height=""}}}static t(t,s,i){let o,p=e.parseVideo(t),_=p.type;if("youtube"===_||"vimeo"===_){let e,d={},r="fullscreen",a=new URL(t);if(p.h&&(d.h=p.h),d.pip=d.playsinline=d.loop=1,d.playlist=p.id,a.search)for(let[e,t]of a.searchParams.entries())"v"!==e&&(d[e]=t);"youtube"===_?(r="accelerometer;encrypted-media;gyroscope;picture-in-picture",e="https://www.youtube",a.hash&&"#"!==a.hash&&(d.start=parseFloat(a.hash.replace("#","")),isNaN(d.start)&&(d.start=0)),e+=".com/embed/"+p.id):(e="https://player.vimeo.com/video/"+p.id,a.hash&&"#"!==a.hash&&(e+=a.hash),d.byline=d.title=d.portrait=0),i||(d.controls=0),("1"===d.autoplay||s)&&(d.autoplay=1,r+=";autoplay"),e+="?"+new URLSearchParams(d),o=l("noscript","bsp_video",'<iframe data-tf-not-load src="'+e+'" allow="'+r+'" class="bsp_video tf_abs tf_w tf_h" ></iframe>')}else{o=l("video",{class:"tf_abs tf_w tf_h bsp_video",decoding:"async",preload:"none",src:t,playsinline:!0,muted:!0,loop:!0});const e=o.dataset;e.tfNotLoad=e.skip=1,s&&(e.autoplay=1),i||(e.hideControls=1)}return o}l(e,s,i,o,p){let _={disable:"disable",shortTop:"up",shortTopOut:"up",longTop:"up",longTopOut:"up",shortLeft:"left",shortLeftOut:"left",longLeft:"left",longLeftOut:"left",skewShortLeft:"left",skewShortLeftOut:"left",skewLongLeft:"left",skewLongLeftOut:"left",shortBottom:"down",shortBottomOut:"down",longBottom:"down",longBottomOut:"down",shortRight:"right",shortRightOut:"right",longRight:"right",longRightOut:"right",skewShortRight:"right",skewShortRightOut:"right",skewLongRight:"right",skewLongRightOut:"right",fade:"up",fadeOut:"up"},d="builder_slider_pro_slides",r="."+("tb_"+this.id)+" .sp-slide-"+p,{builder_ps_slide_type:a="Image",builder_ps_layout:n="bsp-slide-content-left",builder_ps_tranzition_duration:b}=e,u=!(("Image"!==a||e["builder-ps-bg-image"])&&("Video"!==a||e.builder_ps_vbg_option)),m=l("","sp-slide sp-slide-"+p+" sp-slide-type-"+a+" tf_w "+n),c=l("","bsp-layers-overlay tf_w "+("Video"===a?"tf_abs":"tf_rel")),h=l("","sp-slide-wrap"),g=l("","sp-slide-text tf_box"),f=m.dataset,y=this.constructor;if(e["builder-ps-bg-image"]){const t=e["builder-ps-bg-image"];f.bg=t,s.builder_ps_width>0&&s.builder_ps_height>0&&(f.orig=t,ThemifyImageResize.toBlob(m,s.builder_ps_width,s.builder_ps_height).then((e=>{f.orig="",e&&(m.style.backgroundImage="url("+e+")",f.bg=e)})).catch((()=>{})))}if(e["builder-ps-bg-color"]&&o.push(r+":before{background-color:"+ThemifyStyles.toRGBA(e["builder-ps-bg-color"])+"}"),e.builder_ps_text_color&&o.push(r+" .bsp-slide-excerpt,"+r+" .bsp-slide-excerpt p,"+r+" .sp-slide-text .bsp-slide-post-title{color:"+ThemifyStyles.toRGBA(e.builder_ps_text_color)+"}"),e.builder_ps_text_link_color&&o.push(r+" .bsp-slide-excerpt a,"+r+" .bsp-slide-excerpt p a{color:"+ThemifyStyles.toRGBA(e.builder_ps_text_link_color)+"}"),e.builder_ps_button_color&&o.push(r+" a.bsp-slide-button{color:"+ThemifyStyles.toRGBA(e.builder_ps_button_color)+"}"),e.builder_ps_button_bg&&o.push(r+" a.bsp-slide-button{background-color:"+ThemifyStyles.toRGBA(e.builder_ps_button_bg)+"}"),"bsp-slide-content-center"===n&&(m.className+=" tf_textc"),0===p&&(m.className+=" sp-selected"),b="fast"===b?.5:"slow"===b?4:1,f.transition=e.builder_ps_tranzition||"slideTop",f.duration=b,!0===u?m.className+=" bsp-no-background":"Video"===a?m.appendChild(y.t(e.builder_ps_vbg_option,e.autoplay,e.controls)):i.push([e["builder-ps-bg-image"],p]),e["builder-ps-slide-image"]){const t=l("","sp-layer sp-slide-image tf_box tf_left"),i=t.dataset,{builder_ps_imgs_timer:o="shortTop",builder_ps_imge_timer:r="shortTopOut"}=s;"bsp-slide-content-center"===n&&(t.className+=" tf_textc tf_clearfix"),"disable"!==_[o]&&(i.showTransition=_[o],i.hideTransition=_[r],i.hideDuration=i.showDuration=1e3,i.hideDelay=i.showDelay=0),t.appendChild(y.setEditableImage(l("img",{class:"bsp-content-img","data-globalsize":0}),"builder-ps-slide-image","s_c_w","s_c_h",e,d,p)),h.appendChild(t)}if(e.builder_ps_heading){const t=l("h3","sp-layer bsp-slide-post-title"),i=t.dataset,{builder_ps_h3s_timer:o="shortTop",builder_ps_h3e_timer:r="shortTopOut"}=s;"disable"!==_[o]&&(i.showTransition=_[o],i.hideTransition=_[r],i.showDelay=300,i.hideDuration=i.showDuration=1e3,i.hideDelay=0),g.appendChild(y.setEditableContent(t,"builder_ps_heading",e.builder_ps_heading,"",d,p))}if(e.builder_ps_text){const t=l("","sp-layer bsp-slide-excerpt"),i=t.dataset,{builder_ps_ps_timer:o="shortTop",builder_ps_pe_timer:r="shortTopOut"}=s;"disable"!==_[o]&&(i.showTransition=_[o],i.hideTransition=_[r],i.showDelay=600,i.hideDuration=i.showDuration=1e3,i.hideDelay=0),y.setEditableContent(t,"builder_ps_text","",1,d,p),e.builder_ps_text&&(t.innerHTML=this.shortcodeToHTML(e.builder_ps_text).content),g.appendChild(t)}if(e.builder_ps_button_text){let i,o=l("a","sp-layer bsp-slide-button"),r=o.dataset,a=e.builder_ps_button_action_type||"custom",{builder_ps_as_timer:n="shortTop",builder_ps_ae_timer:b="shortTopOut"}=s;switch(a){case"next_slide":i="#next-slide";break;case"prev_slide":i="#prev-slide";break;default:i=e.builder_ps_button_link||"","lightbox"===a?o.className+=" themify_lightbox":"yes"===e.builder_ps_new_tab&&(o.target="_blank");break}if(i){if(o.href=i,"disable"!==_[n]&&(r.showTransition=_[n],r.hideTransition=_[b],r.showDelay=900,r.hideDuration=r.showDuration=1e3,r.hideDelay=0),y.setEditableContent(o,"builder_ps_button_text",e.builder_ps_button_text,"",d,p),e.builder_ps_button_icon){let s=l("i");s.appendChild(t.Helper.getIcon(e.builder_ps_button_icon)),o.prepend(s)}g.appendChild(o)}}return h.appendChild(g),c.appendChild(h),m.append(c,l("",{class:"tb_del_btn tb_del_bspbtn tf_close tb_disable_sorting",role:"button",title:"Delete Slide"})),m}preview(s){const i=l(),o=i.dataset,{builder_ps_triggers_position:p="standard",builder_ps_triggers_type:_="circle",builder_ps_aa:d="off",builder_slider_pro_slides:r=[]}=s,a=r.length,n=["module","module-pro-slider","pager-"+p,"pager-type-"+_];if(s.css_slider_pro&&n.push(s.css_slider_pro),i.className=n.join(" "),o.loop="yes"===s.builder_ps_wrap?1:"",o.autoplay=d,o.hoverPause=s.builder_ps_hover_pause||"pause",o.timerBar="yes"===s.builder_ps_timer?1:"",o.touchSwipeDesktop="yes"===s.touch_swipe_desktop?1:"",o.touchSwipeMobile="yes"===s.touch_swipe_mob?1:"","fullscreen"===s.builder_ps_fullscreen?(o.sliderWidth="100%",o.sliderHeight="100vh"):(o.sliderWidth=s.builder_ps_width||"",o.sliderHeight=s.builder_ps_height||""),s.mod_title_slider&&i.appendChild(this.constructor.getModuleTitle(s.mod_title_slider,"mod_title_slider")),a>0){const o=l("","slider-pro tf_rel tf_hidden tf_lazy"),n=l("","sp-mask tf_rel tf_overflow"),b=l("","sp-slides tf_rel"),u=[],m=[];"off"!==d&&"yes"===s.builder_ps_timer&&o.appendChild(l("","bsp-timer-bar tf_abs_t"));for(let e=0;e<a;++e)b.appendChild(this.l(r[e],s,m,u,e));if(n.appendChild(b),o.appendChild(n),"none"!==p){const e=l("","sp-buttons tf_w");if("thumb"===_){const t=s.builder_ps_thumb_width||30,i=s.builder_ps_thumb_height||30;e.style.height=i+"px";for(let s=0;s<m.length;++s){let o=m[s],p=l("","sp-thumbnail"),_=new Image(t,i);_.className="sp-thumbnail",this.constructor.setEditableImage(_,"builder-ps-bg-image","builder_ps_thumb_width","builder_ps_thumb_height","","builder_slider_pro_slides",o[1]),_.src=o[0],p.dataset.index=o[1],0===s&&(p.className+=" sp-selected-thumbnail"),p.style.width=t+"px",p.appendChild(_),e.appendChild(p)}}else for(let t=0;t<a;++t)e.appendChild(l("","sp-button tf_box"+(0===t?" sp-selected-button":"")));o.appendChild(e)}if(i.tfOn(e.click,(l=>{const s=l.target,i=s?.classList;if(i.contains("tb_add_bspbtn")||i.contains("tb_del_bspbtn")){l.stopPropagation();const o=this,{id:p,el:_}=o;if(i.contains("tb_add_bspbtn"))if(t.activeModel?.id===p)e.triggerEvent(t.LightBox.el.tfClass("add_new")[0],l.type);else{t.undoManager.start("inlineAdd");const e=o.get("mod_settings"),l=o.constructor.default().builder_slider_pro_slides?.[0]||{},s=TfSliderPro.getInstance(_.tfClass("slider-pro")[0]),i=_.style,p=s.slideHeight;e.builder_slider_pro_slides??=[],i.minHeight=isNaN(p)?p:parseInt(p)+"px",e.builder_slider_pro_slides.push(l),o.set("mod_settings",e),o.previewLive(e),setTimeout((()=>{i.minHeight=""}),100),t.undoManager.end("inlineAdd"),s.sliderEl.remove()}else{const i=s.closest(".sp-slide"),d=e.convert(i.parentNode.children).indexOf(i);if(-1!==d)if(t.activeModel?.id===p)e.triggerEvent(t.LightBox.el.tfClass("tb_delete_row")[d],l.type);else{t.undoManager.start("inlineDelete");const e=o.get("mod_settings"),l=_.style,s=TfSliderPro.getInstance(_.tfClass("slider-pro")[0]).slideHeight;e.builder_slider_pro_slides.splice(d,1),o.set("mod_settings",e),l.minHeight=isNaN(s)?s:parseInt(s)+"px",o.previewLive(e),setTimeout((()=>{l.minHeight=""}),100),t.undoManager.end("inlineDelete")}}}}),{passive:!0}),i.append(o,l("",{title:"Add Slide",role:"button",class:"tb_add_btn tb_add_bspbtn tf_plus_icon tb_disable_sorting"})),u.length>0){let e="";for(let t=0;t<u.length;++t)e+=u[t];i.appendChild(l("style","",e))}}return i}},t.isFrontend&&e.loadCss(s.url+"assets/modules/active.min","",tbLocalScript.addons["pro-slider"].ver)})(Themify,tb_app,tb_createElement,themifyBuilder.slider_pro_vars);