<?php
/**
 * Template Breadcrumbs
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'sep' => 'c',
	'sep_c' => '',
	'sep_icon' => '',
	'tag' => 'nav',
	'lb_home' => '',
	'lb_archives' => '',
	'lb_404' => '',
	'hide_network' => 'no',
	'animation_effect' => '',
	'css' => '',
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
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class)
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Breadcrumbs module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	TB_Breadcrumbs_Module::display(array(
		'container' => $fields_args['tag'],
		'labels' => array(
			'home' => $fields_args['lb_home'],
			'archives' => $fields_args['lb_archives'],
			'error_404' => $fields_args['lb_404'],
		),
		'network' => 'no' === $fields_args['hide_network'],
		'separator' => $fields_args['sep'] === 'c' ? $fields_args['sep_c'] : themify_get_icon($fields_args['sep_icon'])
	));
	?>
</div><!-- /Breadcrumbs module -->
