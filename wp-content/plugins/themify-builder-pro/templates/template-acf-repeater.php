<?php
/**
 * Template acf repeater
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH') || !TB_ACF_Repeater_Module::is_available()) {
	return;
}
/* pre-render routine, fix preview */
$isActive = Themify_Builder::$frontedit_active === true;

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$builder_id = $args['builder_id'];
$fields_args = $args['mod_settings']+ array(
	'key' => '',
	'grid_layout' => 'list-post',
	'css' => '',
	'display' => 'grid',
	// Slider
	'visible_opt_slider' => '',
	'mob_visible_opt_slider' => '',
	'tab_visible_opt_slider' => '',
	'auto_scroll_opt_slider' => 0,
	'scroll_opt_slider' => '',
	'speed_opt_slider' => '',
	'effect_slider' => 'scroll',
	'pause_on_hover_slider' => 'resume',
	'play_pause_control' => 'no',
	'pagination' => 'yes',
	'wrap_slider' => 'yes',
	'show_nav_slider' => 'yes',
	'show_arrow_slider' => 'yes',
	'show_arrow_buttons_vertical' => '',
	'left_margin_slider' => '',
	'right_margin_slider' => '',
	'height_slider' => 'variable',
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}

$repeater_field = $fields_args['key'] !== '' ? explode(':', $fields_args['key'])[1] : false;
$context = Themify_Builder_Plugin_Compat_acf::get_context($fields_args);
$isSlider = $fields_args['display'] === 'slider';
if ($isSlider) {
	$margin = '';
	if ($fields_args['left_margin_slider'] !== '') {
		$margin = 'margin-left:' . $fields_args['left_margin_slider'] . 'px;';
	}
	if ($fields_args['right_margin_slider'] !== '') {
		$margin .= 'margin-right:' . $fields_args['right_margin_slider'] . 'px';
	}
	$container_inner = array(
		'data-visible' => $fields_args['visible_opt_slider'],
		'data-tab-visible' => $fields_args['tab_visible_opt_slider'],
		'data-mob-visible' => $fields_args['mob_visible_opt_slider'],
		'data-scroll' => $fields_args['scroll_opt_slider'],
		'data-speed' => $fields_args['speed_opt_slider'] === 'slow' ? 4 : ($fields_args['speed_opt_slider'] === 'fast' ? '.5' : 1),
		'data-wrapvar' => $fields_args['wrap_slider'] !== 'no' ? 1 : 0,
		'data-slider_nav' => $fields_args['show_arrow_slider'] === 'yes' ? 1 : 0,
		'data-pager' => $fields_args['show_nav_slider'] === 'yes' ? 1 : 0,
		'data-effect' => $fields_args['effect_slider'],
		'data-height' => $fields_args['height_slider'],
	);
	if ($container_inner['data-slider_nav'] === 1 && $fields_args['show_arrow_buttons_vertical'] === 'vertical') {
		$container_inner['data-nav_out'] = 1;
		$container_inner['data-css_url'] = THEMIFY_BUILDER_CSS_MODULES . 'sliders/carousel';
		$container_class[] = ' themify_builder_slider_vertical';
	}
	if ($fields_args['auto_scroll_opt_slider'] && $fields_args['auto_scroll_opt_slider'] !== 'off') {
		$container_inner['data-auto'] = $fields_args['auto_scroll_opt_slider'] * 1000;
		$container_inner['data-pause_hover'] = $fields_args['pause_on_hover_slider'] === 'resume' ? 1 : 0;
		$container_inner['data-controller'] = $fields_args['play_pause_control'] === 'yes' ? 1 : 0;
	}
} else {
	$container_class[] = 'builder-posts-wrap loops-wrapper';
	$container_class[] = $fields_args['grid_layout'];
	Themify_Enqueue_Assets::loadGridCss($fields_args['grid_layout']);
}

$container_class[] = 'themify_builder_content-' . str_replace('tb_', '', $element_id); /* needed for Styling of sub modules */

$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
$isV7 = method_exists('Themify_Builder_Model', 'add_module'); //backward
?>
<!-- ACF Repeater module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>

	<?php if ( $isActive===true ) : ?>
		<p class="tbp_module_title"><?php _e( 'ACF Repeater', 'tbp' ); ?></p>
		<?php if ( ! $repeater_field ) {
			_e( 'Edit the module to select the ACF Repeater field', 'tbp' );
		}
	endif; ?>

	<?php
	$container_props = $container_class = $args = null;
	if(isset(Themify_Builder::$is_loop)){
		$isLoop=Themify_Builder::$is_loop;
		Themify_Builder::$is_loop=true;
	}
	else{//backward
		global $ThemifyBuilder;
		$isLoop = $ThemifyBuilder->in_the_loop === true;
		$ThemifyBuilder->in_the_loop = true;
	}
	Tbp_Utils::$isActive = $isActive;
	Themify_Builder::$frontedit_active = false;
	if ($repeater_field && have_rows($repeater_field, $context)) :
		TB_ACF_Repeater_Module::$builder_id = str_replace( 'tb_', '', $element_id );
		?>
		<?php if ($isSlider === true) : ?>
			<div class="themify_builder_slider tf_carousel tf_swiper-container tf_rel tf_overflow"<?php if ($isActive === false) : ?> data-lazy="1"<?php endif; ?> <?php echo themify_get_element_attributes($container_inner); ?>>
				<div class="tf_swiper-wrapper tf_lazy tf_rel tf_w tf_h tf_textc">
		<?php endif; ?>

				<?php while (have_rows($repeater_field, $context)) : the_row(); ?>
					<?php if (!empty($fields_args['builder_content'])) : ?>

						<?php if ($isSlider === true) : ?>
							<div class="tf_swiper-slide">
								<div class="slide-inner-wrap"<?php if (!empty($margin)) : ?> style="<?php echo $margin; ?>"<?php endif; ?>>
						<?php endif; ?>

								<div class="tbp_advanced_archive_wrap acf_row_<?php echo get_row_index(); ?><?php if ($isSlider === false) echo ' post'; ?>">
									<?php
									foreach ($fields_args['builder_content'] as $rows => $row) {
										if (!empty($row)) {
											if ($isV7 === true) {
												Themify_Builder_Component_Row::template($row, $builder_id);
											} else {
												if (!isset($row['row_order'])) {
													$row['row_order'] = $rows;
												}
												Themify_Builder_Component_Row::template($rows, $row, $builder_id, true);
											}
										}
									}
									?>
								</div>

						<?php if ($isSlider === true) : ?>
								</div>
							</div><!-- .tf_swiper-slide -->
						<?php endif; ?>

					<?php endif; ?>
				<?php endwhile; // rows loop   ?>

		<?php if ($isSlider === true) : ?>
				</div><!-- .tf_swiper-wrapper -->
			</div><!-- .themify_builder_slider -->
		<?php endif; ?>

	<?php else : ?>
		<div class="tbp_advanced_archive_wrap"></div>
	<?php endif; ?>
</div><!-- /ACF Repeater module -->
<?php
Themify_Builder::$frontedit_active = $isActive;
TB_ACF_Repeater_Module::$builder_id = null; 
if(isset(Themify_Builder::$is_loop)){
	Themify_Builder::$is_loop=$isLoop;
}
else{//backward
	$ThemifyBuilder->in_the_loop = $isLoop;
}