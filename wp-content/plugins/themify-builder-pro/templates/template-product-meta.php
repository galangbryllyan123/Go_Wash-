<?php
/**
 * Template Product Meta
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
	'enable_cat' => 'yes',
	'cat' => '',
	'enable_tag' => 'yes',
	'tag' => '',
	'enable_sku' => 'yes',
	'sku' => '',
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
$fields_args['mod_name'] = $mod_name;

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Product Meta module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	self::retrieve_template('wc/meta.php', $fields_args, __DIR__);
	?>
</div>
<!-- /Product Meta module -->
