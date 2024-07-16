<?php
/**
 * Template Related Products
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
	'heading' => '',
	'layout' => 'grid3',
	'limit' => '',
	'image_w' => '',
	'image_h' => '',
	'css' => '',
	'animation_effect' => ''
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	'woocommerce',
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false && Tbp_Utils::$isActive===false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Related Products module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
		$container_props = $container_class = $args = null;
		global $product;
		if(is_object($product)){
			if ($fields_args['layout'] === 'grid2') {
				$col = 2;
			} elseif ($fields_args['layout'] === 'grid4') {
				$col = 4;
			} elseif ($fields_args['layout'] === 'grid5') {
				$col = 5;
			} else{
				$col = $fields_args['layout'] === 'grid6'?6:3;
			}

			// Get visible related products then sort them at random.
			$related_products = array_filter(array_map('wc_get_product', wc_get_related_products($product->get_id(), empty($fields_args['limit']) ? $col : $fields_args['limit'], $product->get_upsell_ids())), 'wc_products_array_filter_visible');
			
			if (!empty($related_products)) :
				if (!empty($fields_args['image_w']) || !empty($fields_args['image_h'])) {
					wc_set_loop_prop('related_image_size_w', $fields_args['image_w']);
					wc_set_loop_prop('related_image_size_h', $fields_args['image_h']);
					if (isset($GLOBALS['themify'])) {
						$GLOBALS['themify']->width = $fields_args['image_w'];
						$GLOBALS['themify']->height = $fields_args['image_h'];
					}
					add_filter('woocommerce_product_get_image', array('TB_Related_Products_Module', 'set_image_size'), PHP_INT_MAX, 2);
					add_filter('wp_img_tag_add_srcset_and_sizes_attr', '__return_false', PHP_INT_MAX);
				}
				wc_set_loop_prop('name', 'related');
				wc_set_loop_prop('columns', apply_filters('woocommerce_related_products_columns', $col));
				?>
				<section class="related tbp_posts_wrap">
					<?php if ($fields_args['heading'] !== ''): ?>
						<h2><?php echo $fields_args['heading']; ?></h2>
					<?php endif; ?>

					<?php
					woocommerce_product_loop_start();
					if(isset(Themify_Builder::$is_loop)){
						$isLoop=Themify_Builder::$is_loop;
						Themify_Builder::$is_loop=true;
					}
					else{//backward
						global $ThemifyBuilder;
						$isLoop = $ThemifyBuilder->in_the_loop === true;
						$ThemifyBuilder->in_the_loop = true;
					}
					global $themify,$post;
					if ( isset( $post ) ){
						$saved_post = clone $post;
					}
					if(isset($themify)){
						$temp_content = $themify->display_content;
						$themify->display_content = 'none'; // Hide the product description in Themify themes
					}
					foreach ($related_products as $rel) {
						$post = get_post($rel->get_id());
						setup_postdata($post);
						wc_get_template_part('content', 'product');
					}
					if(isset($temp_content)){
					$themify->display_content = $temp_content;
				}
					/* reset query back to original */
					if ( isset( $saved_post )) {
						$post = $saved_post;
						setup_postdata( $saved_post );
						unset($saved_post);
					}
					woocommerce_product_loop_end();
					if(isset(Themify_Builder::$is_loop)){
						Themify_Builder::$is_loop=$isLoop;
					}
					else{//backward
						$ThemifyBuilder->in_the_loop = $isLoop;
					}
					?>

				</section>
			<?php
			if (!empty($fields_args['image_w']) || !empty($fields_args['image_h'])) {
				remove_filter('woocommerce_product_get_image', array('TB_Related_Products_Module', 'set_image_size'), PHP_INT_MAX);
				remove_filter('wp_img_tag_add_srcset_and_sizes_attr', '__return_false', PHP_INT_MAX);
			}
		endif;
	}
	?>
	<?php if (empty($related_products)&& is_string($mod_name) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true)): ?>
		<div class="tbp_empty_module">
			<?php echo self::get_module_class($mod_name)::get_module_name() ?>
		</div>
	<?php endif; ?>
</div>
<!-- /Related Products module -->
