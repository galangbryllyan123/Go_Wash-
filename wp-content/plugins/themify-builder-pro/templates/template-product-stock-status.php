<?php
/**
 * Template Product Stock Status
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (!defined('ABSPATH') || !themify_is_woocommerce_active()) {
	return;
}
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'in_stock' => '',
	'out_of_stock' => '',
	'css' => '',
	'animation_effect' => ''
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	'product-price',
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args
);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id
);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Product Stock Status -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	global $product;
	if(is_object($product)){
		if ($product->is_in_stock()) {
			echo empty( $fields_args['in_stock'] ) ? '' : '<div class="tbp_product_in_stock">', str_replace('%stock_count%', (string) $product->get_stock_quantity(), $fields_args['in_stock']), '</div>';
		} else {
			echo empty( $fields_args['out_of_stock'] ) ? '' : '<div class="tbp_product_out_of_stock">', $fields_args['out_of_stock'], '</div>';
		}
	}
	?>
</div>
<!-- /Product Stock Status -->