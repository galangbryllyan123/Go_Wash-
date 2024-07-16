<?php
/**
 * Template Product Title
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH') || !themify_is_woocommerce_active()){
	return;
}

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+  array(
	'link' => 'permalink',
	'open_link' => 'regular',
	'lightbox_w_unit' => '%',
	'lightbox_h_unit' => '%',
	'html_tag' => 'h2',
	'no_follow' => 'no',
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
$args = null;
if (Themify_Builder::$frontedit_active === false && Tbp_Utils::$isActive===false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Product Title module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = null;
	themify_product_title_start();
	self::retrieve_template('partials/title.php', $fields_args, __DIR__);
	themify_product_title_end();
	?>
</div>
<!-- /Product Title module -->