<?php
/**
 * Template Field Types
 * 
 * Access original fields: $args['mod_settings']
 */
/* default options for each slide */

defined( 'ABSPATH' ) || exit;

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$slide_defaults = array(
	'builder_ps_slide_type' => 'Image',
	'builder-ps-bg-image' => '',
	'builder_ps_vbg_option' => '',
	'autoplay'=>'',
	'controls'=>'',
	'builder_ps_tranzition' => 'slideTop',
	'builder_ps_layout' => 'bsp-slide-content-left',
	'builder_ps_tranzition_duration' => 'normal',
	'builder-ps-bg-color' => '',
	'builder-ps-slide-image' => '',
	's_c_w' => '',
	's_c_h' => '',
	'builder_ps_heading' => '',
	'builder_ps_text' => '',
	'builder_ps_text_color' => '',
	'builder_ps_text_link_color' => '',
	'builder_ps_button_action_type' => 'custom',
	'builder_ps_button_text' => '',
	'builder_ps_button_link' => '',
	'builder_ps_button_icon' => '',
	'l_w' => '',
	'l_w_unit' => 'px',
	'l_h' => '',
	'l_h_unit' => 'px',
	'builder_ps_h3s_timer' => 'shortTop',
	'builder_ps_h3e_timer' => 'shortTopOut',
	'builder_ps_ps_timer' => 'shortTop',
	'builder_ps_pe_timer' => 'shortTopOut',
	'builder_ps_as_timer' => 'shortTop',
	'builder_ps_ae_timer' => 'shortTopOut',
	'builder_ps_imgs_timer' => 'shortTop',
	'builder_ps_imge_timer' => 'shortTopOut',
	'builder_ps_button_color' => '',
	'builder_ps_button_bg' => '',
);

/* setup element transition fallbacks */
$timer_translation = array(
	'disable' => 'disable',
	'shortTop' => 'up',
	'shortTopOut' => 'up',
	'longTop' => 'up',
	'longTopOut' => 'up',
	'shortLeft' => 'left',
	'shortLeftOut' => 'left',
	'longLeft' => 'left',
	'longLeftOut' => 'left',
	'skewShortLeft' => 'left',
	'skewShortLeftOut' => 'left',
	'skewLongLeft' => 'left',
	'skewLongLeftOut' => 'left',
	'shortBottom' => 'down',
	'shortBottomOut' => 'down',
	'longBottom' => 'down',
	'longBottomOut' => 'down',
	'shortRight' => 'right',
	'shortRightOut' => 'right',
	'longRight' => 'right',
	'longRightOut' => 'right',
	'skewShortRight' => 'right',
	'skewShortRightOut' => 'right',
	'skewLongRight' => 'right',
	'skewLongRightOut' => 'right',
	/* fallbacks: replace all non-existent effects with up */
	'fade' => 'up',
	'fadeOut' => 'up'
);
$fields_args = $args['mod_settings']+array(
	'mod_title_slider' => '',
	'builder_ps_triggers_position' => 'standard',
	'builder_ps_wrap' => '',
	'builder_ps_triggers_type' => 'circle',
	'builder_ps_aa' => 'off',
	'builder_ps_hover_pause' => 'pause',
	'builder_ps_timer' => '',
	'builder_ps_width' => '',
	'builder_ps_height' => '',
	'builder_ps_thumb_width' => 30,
	'builder_ps_thumb_height' => 30,
	'builder_slider_pro_slides' => array(),
	'touch_swipe_desktop' => 'yes',
	'touch_swipe_mob' => 'yes',
	'css_slider_pro' => '',
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module', 'module-' . $mod_name, $element_id, 'pager-' . $fields_args['builder_ps_triggers_position'], 'pager-type-' . $fields_args['builder_ps_triggers_type'], $fields_args['css_slider_pro']
	), $mod_name, $element_id, $fields_args);
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$styles = array();
$height=isset($fields_args['builder_ps_fullscreen']) && $fields_args['builder_ps_fullscreen'] === 'fullscreen' ? '100vh' : $fields_args['builder_ps_height'];
$container_props = apply_filters('themify_builder_module_container_props', array(
	'class' => implode(' ', $container_class),
	'data-loop' => $fields_args['builder_ps_wrap'] === 'yes' ? 1 : '',
	'data-autoplay' => $fields_args['builder_ps_aa'],
	'data-hover-pause' => $fields_args['builder_ps_hover_pause'],
	'data-timer-bar' => $fields_args['builder_ps_timer'] === 'yes' ? 1 : '',
	'data-slider-width' => isset($fields_args['builder_ps_fullscreen']) && $fields_args['builder_ps_fullscreen'] === 'fullscreen' ? '100%' : $fields_args['builder_ps_width'],
	'data-slider-height' => $height,
	'data-touch-swipe-desktop' => $fields_args['touch_swipe_desktop'] === 'yes' ? 1 : '',
	'data-touch-swipe-mobile' => $fields_args['touch_swipe_mob'] === 'yes' ? 1 : '',
	), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
if (isset(Themify_Builder_Component_Module::$isFirstModule) && Themify_Builder_Component_Module::$isFirstModule === true) {
	$assets_url = Builder_Pro_Slider::$url . 'assets/';
	$v = Builder_Pro_Slider::get_version();
	Themify_Enqueue_Assets::addPrefetchJs($assets_url . 'slider-pro.js', '1.3');
	$assets_url .= 'modules/';
}
?>
<!-- Slider Pro module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	if (method_exists('Themify_Builder_Component_Module', 'get_module_title')) {
		echo Themify_Builder_Component_Module::get_module_title($fields_args, 'mod_title_slider');
	} elseif ($fields_args['mod_title_slider'] !== '') {
		echo $fields_args['before_title'], apply_filters('themify_builder_module_title', $fields_args['mod_title_slider'], $fields_args), $fields_args['after_title'];
	}
	do_action('themify_builder_before_template_content_render');
	$thumbs=array();
	$isNew=!empty($fields_args['c']);
	?>
	<?php if (!empty($fields_args['builder_slider_pro_slides'])): ?>
		<div class="slider-pro tf_rel tf_hidden tf_lazy">
			<?php if ($fields_args['builder_ps_timer'] === 'yes' && $fields_args['builder_ps_aa'] !== 'off'): ?>
				<div class="bsp-timer-bar tf_w tf_abs_t"></div>
			<?php endif; ?>
			<div class="sp-mask tf_rel tf_overflow"<?php if($height!==''):?> style="height:<?php echo $height;if(strpos($height,'vh')===false):?>px<?php endif;?>"<?php endif;?>>
				<div class="sp-slides tf_rel">
					<?php foreach ($fields_args['builder_slider_pro_slides'] as $i => $slide) : ?>
						<?php
						$slide+= $slide_defaults;
						$slide_type = $slide['builder_ps_slide_type'];

						$is_empty_slide = ( $slide_type === 'Image' && empty($slide['builder-ps-bg-image']) ) || ( $slide_type === 'Video' && empty($slide['builder_ps_vbg_option']) );
						$slide_background = '';
						if ( ! empty( $slide['builder-ps-bg-image'] ) ) {
							if ($fields_args['builder_ps_width'] === '' && $fields_args['builder_ps_height'] === '') {
								themify_generateWebp($slide['builder-ps-bg-image']);
								$slide_background = $slide['builder-ps-bg-image'];
							} else {
								$slide_background = themify_get_image(array('src' => $slide['builder-ps-bg-image'], 'w' => $fields_args['builder_ps_width'], 'h' => $fields_args['builder_ps_height'], 'urlonly' => true));
							}
							$slide_background = $i === 0 && isset($assets_url) ? sprintf(' style="background-image:url(%s)"', $slide_background) : sprintf(' data-bg="%s"', $slide_background);
						}
						// slide styles
						if (!empty($slide['builder-ps-bg-color'])) {
							$styles[] = sprintf('.sp-slide-%s:before{background-color:%s}', $i, Themify_Builder_Stylesheet::get_rgba_color($slide['builder-ps-bg-color']));
						}
						if ('' !== $slide['builder_ps_text_color']) {
							$styles[] = explode(',', sprintf('.sp-slide-%1$s .bsp-slide-excerpt,.sp-slide-%1$s .bsp-slide-excerpt p,.sp-slide-%1$s .sp-slide-text .bsp-slide-post-title{color:%2$s}', $i, Themify_Builder_Stylesheet::get_rgba_color($slide['builder_ps_text_color'])));
						}
						if ('' !== $slide['builder_ps_text_link_color']) {
							$styles[] = explode(',', sprintf('.sp-slide-%1$s .bsp-slide-excerpt a,.sp-slide-%1$s .bsp-slide-excerpt p a{color:%2$s}', $i, Themify_Builder_Stylesheet::get_rgba_color($slide['builder_ps_text_link_color'])));
						}
						if ('' !== $slide['builder_ps_button_color']) {
							$styles[] = sprintf('.sp-slide-%1$s a.bsp-slide-button{color:%2$s}', $i, Themify_Builder_Stylesheet::get_rgba_color($slide['builder_ps_button_color']));
						}
						if ('' !== $slide['builder_ps_button_bg']) {
							$styles[] = sprintf('.sp-slide-%1$s a.bsp-slide-button{background-color:%2$s}', $i, Themify_Builder_Stylesheet::get_rgba_color($slide['builder_ps_button_bg']));
						}
						?>
						<div class="sp-slide sp-slide-<?php echo $i; ?> sp-slide-type-<?php echo $slide_type; ?> <?php if ($i === 0): ?> sp-selected<?php endif; ?> <?php echo $slide['builder_ps_layout'] . ($slide['builder_ps_layout'] === 'bsp-slide-content-center' ? ' tf_textc' : ''); ?><?php if ($is_empty_slide === true) echo ' bsp-no-background'; ?> tf_w" data-transition="<?php echo $slide['builder_ps_tranzition']; ?>" data-duration="<?php echo Builder_Pro_Slider::get_speed($slide['builder_ps_tranzition_duration']); ?>" <?php echo $slide_background; ?>>
							<?php
							if ($is_empty_slide === false) {
								/* slider thumbnail */
								if($fields_args['builder_ps_triggers_type'] === 'thumb' && $fields_args['builder_ps_triggers_position'] !== 'none'){
									$thumbs[] = array($slide['builder-ps-bg-image'],$i);
								}
								if ($slide_type === 'Video') {
									$iframe = themify_get_embed($slide['builder_ps_vbg_option'], array('loop'=>1,'autoplay'=>$slide['autoplay'],'hide_controls'=>($isNew===true && $slide['controls']===''),'disable_lazy' => true,'class'=>'bsp_video tf_abs tf_w tf_h'));
									if ($iframe!==''):?>
										<noscript class="bsp_video">
											<?php echo $iframe;?>
										</noscript>
									<?php else: ?>
										<video data-tf-not-load data-skip decoding="async" preload="none" src="<?php echo $slide['builder_ps_vbg_option'] ?>" class="bsp_video tf_abs tf_w tf_h" playsinline muted<?php echo $fields_args['builder_ps_aa'] === 'off' ? ' loop' : ''; ?><?php echo $slide['controls'] === '' ? ' data-hide-controls' : ''; ?> <?php echo ($isNew===false || $slide['autoplay']!=='')? ' data-autoplay':''?>></video>
									<?php
									endif;
								}
							}
							?>
							<div class="bsp-layers-overlay<?php echo $slide_type === 'Video' ? ' tf_abs' : ' tf_rel' ?> tf_w">
								<div class="sp-slide-wrap">
									<?php if (!empty($slide['builder-ps-slide-image'])) : ?>
										<div class="sp-layer sp-slide-image tf_box"
										<?php if ('disable' !== $timer_translation[$slide['builder_ps_imgs_timer']]): ?>
												 data-show-transition="<?php echo $timer_translation[$slide['builder_ps_imgs_timer']]; ?>"
												 data-hide-transition="<?php echo $timer_translation[$slide['builder_ps_imge_timer']]; ?>"
												 data-show-duration="1000"
												 data-show-delay="0"
												 data-hide-duration="1000"
												 data-hide-delay="0"
											 <?php endif; ?>
											 > 	
											<?php
												echo themify_get_image(array('src' => $slide['builder-ps-slide-image'],'w' => $slide['s_c_w'], 'h' => $slide['s_c_h'], 'is_slider'=>true,'alt'=>Themify_Builder_Model::get_alt_by_url($slide['builder-ps-slide-image']),'class'=>'bsp-content-img'));
											?>
										</div>
									<?php endif; ?>
									<div class="sp-slide-text tf_box">
										<?php if ($slide['builder_ps_heading'] !== '') : ?>
											<h3 class="sp-layer bsp-slide-post-title"
											<?php if ('disable' !== $timer_translation[$slide['builder_ps_h3s_timer']]): ?>
													data-show-transition="<?php echo $timer_translation[$slide['builder_ps_h3s_timer']]; ?>"
													data-hide-transition="<?php echo $timer_translation[$slide['builder_ps_h3e_timer']]; ?>"
													data-show-duration="1000"
													data-show-delay="300"
													data-hide-duration="1000"
													data-hide-delay="0"
												<?php endif; ?>><?php echo $slide['builder_ps_heading']; ?>
											</h3>
										<?php endif; ?>

										<?php if ($slide['builder_ps_text'] !== '') : ?>
											<div class="sp-layer bsp-slide-excerpt"
											<?php if ('disable' !== $timer_translation[$slide['builder_ps_ps_timer']]): ?>
													 data-show-transition="<?php echo $timer_translation[$slide['builder_ps_ps_timer']]; ?>"
													 data-hide-transition="<?php echo $timer_translation[$slide['builder_ps_pe_timer']]; ?>"
													 data-show-duration="1000"
													 data-show-delay="600"
													 data-hide-duration="1000"
													 data-hide-delay="0"
												 <?php endif; ?>><?php echo apply_filters('themify_builder_module_content', $slide['builder_ps_text']); ?>
											</div>
											<?php
											if (isset($assets_url)) {
												Themify_Builder_Model::loadCssModules('bsp_excerpt', $assets_url . 'excerpt.css', $v);
											}
											?>
										<?php endif; ?>

										<?php
										if ('' !== $slide['builder_ps_button_text']):
											$link_attr = array('class' => 'sp-layer bsp-slide-button');
											switch ($slide['builder_ps_button_action_type']) {
												case 'next_slide':
													$link_attr['href'] = '#next-slide';
													break;
												case 'prev_slide':
													$link_attr['href'] = '#prev-slide';
													break;
												case 'lightbox':
													$link_attr['href'] = esc_url($slide['builder_ps_button_link']);
													$link_attr['class'] .= ' themify_lightbox';
													if ($slide['l_w'] !== '' || $slide['l_h'] !== '') {
														$lightbox_settings = array();
														if ($slide['l_w'] !== '') {
															$lightbox_settings[] = $slide['l_w'] . $slide['l_w_unit'];
														}
														if ($slide['l_h'] !== '') {
															$lightbox_settings[] = $slide['l_h'] . $slide['l_h_unit'];
														}
														$link_attr['data-zoom-config'] = implode('|', $lightbox_settings);
														unset($lightbox_settings);
													}
													break;
												default :
													$link_attr['href'] = esc_url($slide['builder_ps_button_link']);
													if (isset($slide['builder_ps_new_tab']) && $slide['builder_ps_new_tab'] === 'yes') {
														$link_attr['target'] = '_blank';
													}
													break;
											}
											?>
											<?php if (!empty($link_attr['href'])) : ?>
												<a <?php echo themify_get_element_attributes($link_attr) ?>
													<?php if ('disable' !== $timer_translation[$slide['builder_ps_as_timer']]): ?>
														data-show-transition="<?php echo $timer_translation[$slide['builder_ps_as_timer']]; ?>"
														data-hide-transition="<?php echo $timer_translation[$slide['builder_ps_ae_timer']]; ?>"
														data-show-duration="1000"
														data-show-delay="900"
														data-hide-duration="1000"
														data-hide-delay="0"
													<?php endif; ?>
													>
														<?php if ('' !== $slide['builder_ps_button_icon']): ?>
														<i><?php echo themify_get_icon($slide['builder_ps_button_icon']) ?></i>
													<?php endif; ?>
													<?php echo $slide['builder_ps_button_text']; ?>
												</a>
												<?php
												if (isset($assets_url)) {
													Themify_Builder_Model::loadCssModules('bsp_button', $assets_url . 'button.css', $v);
												}
												?>
											<?php endif; ?>
										<?php endif; ?>
									</div>
									<!-- /sp-slide-text -->
								</div><!-- .sp-slide-wrap -->
							</div><!-- .bsp-layers-overlay -->
						</div><!-- .sp-slide -->
					<?php endforeach; ?>
				</div><!-- .sp-slides -->
			</div>
			<?php if ($i > 0 && $fields_args['builder_ps_triggers_position'] !== 'none'): ?>
				<div class="sp-buttons tf_w"<?php if(!empty($thumbs)):?> style="height:<?php echo $fields_args['builder_ps_thumb_height']?>px"<?php endif;?>>
					<?php if (!empty($thumbs)):
						foreach($thumbs as $i=>$arr):?>
							<?php 
								$url=themify_get_image(array('src' => $arr[0],'w' => $fields_args['builder_ps_thumb_width'], 'h' => $fields_args['builder_ps_thumb_height'],'urlonly'=>true));
							?>
							<div data-index="<?php echo $arr[1]?>" class="sp-thumbnail<?php if ($i===0): ?> sp-selected-thumbnail<?php endif; ?>" style="width:<?php echo $fields_args['builder_ps_thumb_width']?>px">
								<img src="data:image/gif;base64,R0lGODlhAQABAAAAACwAAAAAAQABAAA=" width="<?php echo $fields_args['builder_ps_thumb_width']?>" height="<?php echo $fields_args['builder_ps_thumb_height']?>" alt="<?php esc_attr_e(Themify_Builder_Model::get_alt_by_url($arr[0]))?>" data-lazy="1" data-tf-src="<?php echo $url?>">
							</div>
						<?php endforeach; ?>
					<?php else:?>
						<?php for ($j = $i; $j > -1; --$j): ?>
							<div class="sp-button tf_box<?php if ($j === $i): ?> sp-selected-button<?php endif; ?>"></div>
						<?php endfor; ?>
					<?php endif;?>
				</div>
			<?php endif; ?>
		</div><!-- .slider-pro -->
	<?php endif; ?>
	<?php
	$slide_defaults = $timer_translation =$thumbs= null;
	do_action('themify_builder_after_template_content_render');

	// add styles
	if (!empty($styles)) {
		echo "<style>\n";
		foreach ($styles as $style) {
			if (is_array($style)) {
				echo '.', $element_id, ' ', implode(',.' . $element_id . ' ', $style), "\n";
			} else {
				echo '.', $element_id, ' ', $style, "\n";
			}
		}
		echo '</style>';
	}
	?>
</div>
<!-- /Slider Pro module -->
