<?php
/**
 * Template WooCommerce Breadcrumb
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (!defined('ABSPATH') || !themify_is_woocommerce_active()){
	return;
}
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'sep' => '',
	'css' => '',
	'animation_effect' => ''
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
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- WooCommerce Breadcrumb module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	woocommerce_breadcrumb(array(
		'delimiter' => ' ' . $fields_args['sep'] . ' '
	));
	?>
</div>
<!-- /WooCommerce Breadcrumb module -->
