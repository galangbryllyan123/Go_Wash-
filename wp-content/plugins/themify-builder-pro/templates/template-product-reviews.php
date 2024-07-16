<?php
/**
 * Template Product Reviews
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
	'description' => 'yes',
	'additionaly' => 'yes',
	'reviews' => 'yes',
	'layout' => '',
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
<!-- Product Reviews module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	TB_Product_Reviews_Module::$elId = $element_id;
	TB_Product_Reviews_Module::$hasDescription = $fields_args['description'] === 'yes';
	TB_Product_Reviews_Module::$hasAdditionaly = $fields_args['additionaly'] === 'yes';
	TB_Product_Reviews_Module::$hasReviews = $fields_args['reviews'] === 'yes';
	if (Themify_Builder::$frontedit_active === true) {
		global $withcomments;
		$withcomments = true;
	}
	if ('accordion' === $fields_args['layout']) {
		add_filter('wc_get_template', array('TB_Product_Reviews_Module', 'accordion_layout'), 10, 5);
	}
	add_filter('woocommerce_product_tabs', array('TB_Product_Reviews_Module', 'getTabs'));
	ob_start();
	woocommerce_output_product_data_tabs();
	remove_filter('woocommerce_product_tabs', array('TB_Product_Reviews_Module', 'getTabs'));
	if ('accordion' === $fields_args['layout']) {
		remove_filter('wc_get_template', array('TB_Product_Reviews_Module', 'accordion_layout'), 10);
	}
	$output = ob_get_clean();
	?>
	<div class="product<?php echo true === TB_Product_Reviews_Module::$singleTab ? ' tbp_single_tab' : ''; ?>">
		<?php echo $output; ?>
	</div>
	<?php
	$output = null;
	?>
</div>
<!-- /Product Reviews module -->