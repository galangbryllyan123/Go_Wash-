((e,t)=>{"use strict";e.ModulePtbRepeater=class extends e.Module{constructor(e){super(e)}static getOptions(){return[{id:"builder_content",type:"tbp_advanched_layout",control:!1},{id:"key",type:"select",label:"tbp_fname",wrap_class:"tb_disable_dc",dataset:"tbp_ptb_key",control:!1,required:{message:"tbp_reqrepf",rule:"not_empty"},binding:{empty:{hide:"builder_content"},not_empty:{show:"builder_content"}}},{id:"display",type:"radio",label:"dispas",options:[{value:"grid",name:"grid"},{value:"slider",name:"slider"}],option_js:!0},{type:"group",options:[{id:"slider",type:"slider"}],wrap_class:"tb_group_element_slider"},{id:"grid_layout",type:"layout",label:"lay",mode:"sprite",control:{classSelector:".builder-posts-wrap"},post_grid:!0,wrap_class:"tb_group_element_grid"},{type:"custom_css_id",custom_css:"css"}]}static getAnimation(){return!1}static builderSave(e){let t={display:"grid",grid_layout:"list-post"},_=e.builder_content,i=e.display||"grid";for(let _ in t)e[_]===t[_]&&delete e[_];delete e.items_per_slide,e.key&&"slider"!==i||(delete e.grid_layout,e.key||(delete e.display,delete e.builder_content,i=_=null)),this.clearSliderOptions(e,"slider"!==i),_&&("string"==typeof _&&(_=JSON.parse(_)),this.cleanBuilderType(_,"row")),super.builderSave(e)}},Themify.on("tb_editing_ptb-repeater_setting",(_=>{_.querySelector("#key").tfOn("change",(function(){if(""!==this.value){const i=this.value.split(":")[2],l=_.querySelector("#builder_content"),o=function(e){return"video"===e?'[{"element_id":"gfur420","cols":[{"element_id":"gfur421","grid_class":"col-full","modules":[ {"element_id":"lqhd151","elType":"module","mod_name":"video","mod_settings":{"mute_video":"no","__dc__":{"url_video":{"show":"url","field":"__FIELD_NAME__","item":"PTBVideo"},"title_video":{"show":"title","field":"__FIELD_NAME__","item":"PTBVideo"},"caption_video":{"show":"description","field":"__FIELD_NAME__","item":"PTBVideo"}},"title_tag":"h3","unit_video":"px","loop":"no","autoplay_video":"no","ext_branding":"no","ext_hide_ctrls":"no","style_video":"video-top"},"component_name":"module"} ]}]}]':"audio"===e?'[{"element_id":"gfur420","cols":[{"element_id":"gfur421","grid_class":"col-full","modules":[ {"element_id":"gvac426","elType":"module","mod_name":"text","mod_settings":{"__dc__":{"mod_title_text":{"show":"title","field":"__FIELD_NAME__","item":"PTBAudio"},"content_text":{"show":"url","field":"__FIELD_NAME__","item":"PTBAudio"}}},"component_name":"module"}, {"element_id":"eqq2487","elType":"module","mod_name":"text","mod_settings":{"__dc__":{"content_text":{"show":"description","field":"__FIELD_NAME__","item":"PTBAudio"}},"margin_bottom":"20"},"component_name":"module"} ]}]}]':"file"===e?'[{"element_id":"gfur420","cols":[{"element_id":"gfur421","grid_class":"col-full","modules":[ {"element_id":"5tj7724","elType":"module","mod_name":"buttons","mod_settings":{"content_button":[{"link":"","link_options":"regular","button_color_bg":"orange","icon":"ti-save-alt","icon_alignment":"left"}],"__dc__":{"content_button":{"0":{"label":{"show":"title","field":"__FIELD_NAME__","item":"PTBFile"},"link":{"show":"url","field":"__FIELD_NAME__","uri_scheme":"0","item":"PTBFile"}},"repeatable":1}},"download_link":"yes","display":"buttons-horizontal","buttons_shape":"normal","buttons_size":"normal","disp":"inline-block"},"component_name":"module"} ]}]}]':"slider"===e||"gallery"===e?'[{"element_id":"gfur420","cols":[{"element_id":"gfur421","grid_class":"col-full","modules":[ {"element_id":"wznk445","elType":"module","mod_name":"image","mod_settings":{"__dc__":{"url_image":{"show":"url","field":"__FIELD_NAME__","item":"PTBGalleryAsText"},"title_image":{"show":"title","field":"__FIELD_NAME__","item":"PTBGalleryAsText"},"link_image":{"show":"link","field":"__FIELD_NAME__","uri_scheme":"0","item":"PTBGalleryAsText"},"caption_image":{"show":"description","field":"__FIELD_NAME__","item":"PTBGalleryAsText"}},"image_zoom_icon":false,"title_tag":"h3","auto_fullwidth":false,"appearance_image":false,"caption_on_overlay":false,"style_image":"image-top","url_image":"__TRANSPARENT_IMAGE__"},"component_name":"module"} ]}]}]':"accordion"===e?'[{"element_id":"gfur420","cols":[{"element_id":"gfur421","grid_class":"col-full","modules":[ {"element_id":"gnii791","elType":"module","mod_name":"accordion","mod_settings":{"content_accordion":[{"default_accordion":"closed"}],"__dc__":{"content_accordion":{"0":{"title_accordion":{"show":"title","field":"__FIELD_NAME__","item":"PTBAcc"},"text_accordion":{"show":"body","field":"__FIELD_NAME__","item":"PTBAcc","o":""}},"repeatable":1}},"hashtag":"no","expand_collapse_accordion":"toggle","margin_bottom":"0"},"component_name":"module"} ]}]}]':"text"===e?'[{"element_id":"gfur420","cols":[{"element_id":"gfur421","grid_class":"col-full","modules":[ {"element_id":"gvac426","elType":"module","mod_name":"text","mod_settings":{"__dc__":{"content_text":{"field":"__FIELD_NAME__","item":"PTBText"}}},"component_name":"module"}]}]}]':void 0}(i).replaceAll("__FIELD_NAME__",this.value).replaceAll("__TRANSPARENT_IMAGE__",t.placeholder_image);l.value=o,e.isVisual&&(ThemifyConstructor.settings.key=this.value,ThemifyConstructor.settings.builder_content=o,Themify.triggerEvent(_.querySelector("#display input:checked"),"change"))}}),{passive:!0})}))})(tb_app,themifyBuilder.ptbRepeater);