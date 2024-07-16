<?php
/**
 * Template Upsell Products
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH') || !themify_is_woocommerce_active()){
	return;
}

global $product;
$upsell_ids = is_object($product)?$product->get_upsell_ids():null;
if (empty($upsell_ids) && Tbp_Utils::$isActive !== true && Themify_Builder::$frontedit_active !== true) {
	return;
}
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'heading' => '',
	'layout' => 'grid3',
	'image_w' => '',
	'image_h' => '',
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

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Upsell Products module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	if (!empty($upsell_ids)) :
		$col = (int) ltrim( $fields_args['layout'], 'grid' );
		$count = count( $upsell_ids );

		$query_args = apply_filters(
			'woocommerce_upsell_display_args', array(
			'posts_per_page' => $count,
			'columns' => $col
			)
		);
		wc_set_loop_prop('name', 'up-sells');
		wc_set_loop_prop('columns', apply_filters('woocommerce_upsells_columns', isset($query_args['columns']) ? $query_args['columns'] : $columns ));

		if (!empty($fields_args['image_w']) || !empty($fields_args['image_h'])) {
			wc_set_loop_prop('image_size_w', $fields_args['image_w']);
			wc_set_loop_prop('image_size_h', $fields_args['image_h']);
			if (isset($GLOBALS['themify'])) {
				$GLOBALS['themify']->width = $fields_args['image_w'];
				$GLOBALS['themify']->height = $fields_args['image_h'];
			}
			add_filter('woocommerce_product_get_image', array('TB_Upsell_Products_Module', 'set_image_size'), PHP_INT_MAX, 2);
			add_filter('wp_img_tag_add_srcset_and_sizes_attr', '__return_false', PHP_INT_MAX);
		}
		?>

		<div class="upsells products tbp_posts_wrap <?php echo $fields_args['layout']; ?> tf_clearfix">

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
			global $post,$themify;
			if ( isset( $post ) ){
				$saved_post = clone $post;
			}
			if(isset($themify)){
				$temp_content = $themify->display_content;
				$themify->display_content = 'none'; // Hide the product description in Themify themes
			}
			foreach ($upsell_ids as $upsell) {
				$post = get_post($upsell);
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

		</div>
		<?php
		if (!empty($fields_args['image_w']) || !empty($fields_args['image_h'])) {
			remove_filter('woocommerce_product_get_image', array('TB_Upsell_Products_Module', 'set_image_size'), PHP_INT_MAX);
			remove_filter('wp_img_tag_add_srcset_and_sizes_attr', '__return_false', PHP_INT_MAX);
		}
	endif;
	?>

<?php if (empty($upsell_ids)&& is_string($mod_name)): ?>
	<div class="tbp_empty_module">
		<?php echo self::get_module_class($mod_name)::get_module_name() ?>
	</div>
<?php endif; ?>
</div><!-- /Upsell Products module -->