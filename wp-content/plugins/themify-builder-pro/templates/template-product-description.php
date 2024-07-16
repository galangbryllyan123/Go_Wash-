<?php
/**
 * Template Product Description
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH')  || !themify_is_woocommerce_active()){
	return;
}
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'description' => 'long',
	'l_b_c' => '',
	'css' => '',
	'animation_effect' => ''
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false && Tbp_Utils::$isActive===false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false && Tbp_Utils::$isActive===false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Product Description module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	self::retrieve_template('wc/description.php', array('l_b_c' => $fields_args['l_b_c'], 'description' => $fields_args['description'], 'mod_name' => $mod_name), __DIR__);
	?>
</div>
<!-- /Product Description module -->
