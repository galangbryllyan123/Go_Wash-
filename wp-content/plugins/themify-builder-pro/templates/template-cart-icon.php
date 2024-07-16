<?php
/**
 * Template Cart Icon
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH') || !themify_is_woocommerce_active()){
	return;
}

$element_id = $args['module_ID'];
$mod_name = $args['mod_name'];
$fields_args = $args['mod_settings']+ array(
	'icon' => 'ti-shopping-cart',
	't' => 'i',
	'txt' => '',
	'style' => 'slide',
	'bubble' => 'off',
	'sub_total' => 'off',
	'show_cart' => '0',
	'animation_effect' => '',
	'css' => '',
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'] . ' tbp_cart_icon_style_' . $fields_args['style']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false && Tbp_Utils::$isActive===false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);


$cart_is_dropdown = 'dropdown' === $fields_args['style'];
if ($cart_is_dropdown === false) {
	$container_props['data-id'] = $element_id;
}
if ($fields_args['show_cart'] !== '0') {
	$container_props['data-show'] = (int) $fields_args['show_cart'];
}
if (Themify_Builder::$frontedit_active === false && Tbp_Utils::$isActive===false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Cart Icon module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	global $woocommerce;
	if(isset($woocommerce->cart)){
		$total = $woocommerce->cart->get_cart_contents_count();
	?>
		<div class="tbp_cart_icon_container">
			<a href="<?php echo $cart_is_dropdown === true ? 'javascript:;' : '#' . $element_id . '_tbp_cart'; ?>">
				<?php if ('on' === $fields_args['sub_total']): ?>
					<span class="tbp_cart_amount"><?php echo $woocommerce->cart->get_cart_subtotal(); ?></span>
				<?php endif; ?>
				<?php echo 'on' !== $fields_args['sub_total'] && 'on' !== $fields_args['bubble'] ? sprintf('<span class="screen-reader-text">%s</span>', __('Cart', 'tbp')) : ''; ?>
				<i class="tbp_shop_cart_icon">
					<?php echo $fields_args['t'] === 'i' ? themify_get_icon($fields_args['icon']) : (function_exists('themify_get_lottie') ? themify_get_lottie($fields_args, 'parent') : ''); ?>
					<?php if ('on' === $fields_args['bubble']): ?>
						<span class="tbp_cart_count <?php echo $total <= 0 ? 'tbp_cart_empty tf_hide' : 'tf_inline_b'; ?> tf_textc"><?php echo $total; ?></span>
					<?php endif; ?>
				</i>
				<?php if ( $fields_args['txt'] !== '' ) : ?>
					<span class="tbp_cart_text"><?php echo esc_html( $fields_args['txt'] ); ?></span>
				<?php endif; ?>
			</a>
			<?php if ($cart_is_dropdown === false): ?>
				<div id="<?php echo $element_id; ?>_tbp_cart" class="tbp_sidemenu sidemenu-off tbp_slide_cart tf_scrollbar tf_textl tf_h tf_hide">
					<a id="<?php echo $element_id; ?>_tbp_close" class="tf_close tbp_cart_icon_close"></a>
				<?php endif; ?>

				<?php self::retrieve_template('wc/shopdock.php', array(), __DIR__); ?>

				<?php if ($cart_is_dropdown === false): ?>
				</div>
				<!-- /#slide-cart -->
			<?php endif; ?>
		</div>
	<?php }?>
</div>
<!-- /Cart Icon module -->

