<?php
/**
 * Template Progress Bar
 * 
 * Access original fields: $args['mod_settings']
 */
defined( 'ABSPATH' ) || exit;

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];

$bar_default = array(
	'bar_label' => '',
	'bar_percentage' => 80,
	'bar_percentage_from' => '',
	'bar_percentage_only_label' => '',
	'bar_percentage_label' => '',
	'bar_color' => '#4a54e6'
);

$fields_args = $args['mod_settings']+ array(
	'mod_title_progressbar' => '',
	'progress_bars' => array(),
	'hide_percentage_text' => 'no',
	'add_css_progressbar' => '',
	'animation_effect' => '',
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module', 'module-' . $mod_name, $element_id, $fields_args['add_css_progressbar']
	), $mod_name, $element_id, $fields_args);
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);
if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- module progress bar -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	echo Themify_Builder_Component_Module::get_module_title($fields_args, 'mod_title_progressbar');
	do_action('themify_builder_before_template_content_render');
	?>
	<?php if (!empty($fields_args['progress_bars'])): ?>
		<div class="tb-progress-bar-wrap">
			<?php foreach ($fields_args['progress_bars'] as $key => $bar) : ?>
				<?php
				$bar+= $bar_default;
				$label_amount = !empty($bar['bar_percentage_from']) ? $bar['bar_percentage_from'] * $bar['bar_percentage'] / 100 : $bar['bar_percentage'];

				if (!empty($bar['bar_percentage_label']) && !empty($bar['bar_percentage_only_label'])) {
					$label_amount = '';
				}
				?>
				<div class="tb-progress-bar tf_textl tf_rel">
					<i class="tb-progress-bar-label tf_w"><?php echo $bar['bar_label']; ?></i>
					<span class="tb-progress-bar-bg" data-percent="<?php echo $bar['bar_percentage'] ?>" style="width:0;background-color:<?php echo Themify_Builder_Stylesheet::get_rgba_color($bar['bar_color']); ?>">
						<?php if ('no' === $fields_args['hide_percentage_text']) : ?>
							<span class="tb-progress-tooltip tf_textr" data-to="<?php echo $label_amount; ?>" data-suffix="<?php echo !empty($bar['bar_percentage_label']) ? ' ' . $bar['bar_percentage_label'] : '%'; ?>"></span>
						<?php endif; ?>
					</span>
				</div><!-- .tb-progress-bar -->
			<?php endforeach; ?>
		</div><!-- .tb-progress-bar-wrap -->
	<?php endif; ?>
	<?php do_action('themify_builder_after_template_content_render'); ?>
</div>
<!-- /module progress bar -->
