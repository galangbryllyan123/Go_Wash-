<?php
/**
 * Template Add To Cart
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH') || !TB_Add_To_Cart_Module::is_available()){
	return;
}

$element_id = $args['module_ID'];
$mod_name = $args['mod_name'];
$fields_args = $args['mod_settings']+ array(
	'quantity' => '',
	'label' => '',
	'label_variable' => '',
	'variable_cart' => '',
	'label_outofstock' => '',
	'fullwidth' => '',
	'css' => '',
	'animation_effect' => ''
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	'tf_rel'
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['fullwidth']) && 'no' !== $fields_args['fullwidth']) {
	$container_class[] = 'buttons-fullwidth';
}
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);


/* this module is used on both single and archive product pages */
$is_single = is_product();
if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Add To Cart module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	global $product;
	if(is_object($product)){
		$type = $product->get_type();
		if ( ! $product->is_in_stock() ) {
			$label = $fields_args['label_outofstock'];
		} else if (!$is_single && $type === 'variable') {
			$label = $fields_args['label_variable'];
		} else {
			$label = $fields_args['label'];
		}

		if (!empty($label)) {
			TB_Add_To_Cart_Module::$cartText = $label;
			if ($is_single) {
				add_filter( $product->is_in_stock() ? 'woocommerce_product_single_add_to_cart_text' : 'woocommerce_get_availability_text', array( 'TB_Add_To_Cart_Module', 'changeCartText' ) );
			} else {
				add_filter('woocommerce_product_add_to_cart_text', array('TB_Add_To_Cart_Module', 'changeCartText'));
			}
		}
		if ($fields_args['quantity'] !== 'yes') {
			add_filter('wc_get_template', array('TB_Add_To_Cart_Module', 'filterQuantityInput'), 10, 5);
		} elseif (!$is_single && $type === 'simple') {// on archive pages WC by default doesn't show Quantity field 
			?>
			<div class="quantity">
				<input type="number" class="input-text qty text filled" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" inputmode="numeric" onchange="this.parentElement.nextElementSibling.setAttribute('data-quantity', this.value);">
			</div>
			<?php
		}

		if ($is_single || ( $fields_args['variable_cart'] === 'yes' && $type === 'variable' )) {
			woocommerce_template_single_add_to_cart();
		} else {
			woocommerce_template_loop_add_to_cart();
		}

		// reset filters
		remove_filter('woocommerce_get_availability_text', array('TB_Add_To_Cart_Module', 'changeCartText'));
		remove_filter('woocommerce_product_single_add_to_cart_text', array('TB_Add_To_Cart_Module', 'changeCartText'));
		remove_filter('woocommerce_product_add_to_cart_text', array('TB_Add_To_Cart_Module', 'changeCartText'));
		remove_filter('wc_get_template', array('TB_Add_To_Cart_Module', 'filterQuantityInput'));
	}
	?>
</div>
<!-- /Add To Cart module -->
