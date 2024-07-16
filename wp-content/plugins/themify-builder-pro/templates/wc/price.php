<?php
/**
 * Template Product Price
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

global $product;
if (!is_object($product) || !themify_is_woocommerce_active()){
	return;
}

if (((isset($args['sale_percentage']) && $args['sale_percentage'] === 'y') || isset($args['hide_regular_price']) && $args['hide_regular_price'] === 'y') && $product->is_on_sale()===true && (class_exists('\TB_Product_Price_Module', false) || self::load_modules('product-price',true)!=='')) {
	$is_on_sale=true;
	if (isset($args['sale_percentage']) && $args['sale_percentage'] === 'y') {
		TB_Product_Price_Module::$cache_sale_label = $args['sale_percentage_lbl']??'';
		add_filter('woocommerce_get_price_html', ['TB_Product_Price_Module', 'woocommerce_get_price_html'], 10, 2);
	}
	if (isset($args['hide_regular_price']) && $args['hide_regular_price'] === 'y') {
		add_filter('woocommerce_format_sale_price', ['TB_Product_Price_Module', 'woocommerce_format_sale_price'], 10, 3);
	}
}
if(isset($args['is_single'])){
	woocommerce_template_single_price();
}else{
	woocommerce_template_loop_price();
}
if (!empty($is_on_sale)) {
	TB_Product_Price_Module::$cache_sale_label = '';
	remove_filter('woocommerce_get_price_html', ['TB_Product_Price_Module', 'woocommerce_get_price_html'], 10);
	remove_filter('woocommerce_format_sale_price', ['TB_Product_Price_Module', 'woocommerce_format_sale_price'], 10);
}
