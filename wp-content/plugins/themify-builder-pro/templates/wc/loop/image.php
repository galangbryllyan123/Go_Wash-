<?php
global $product;
if(!is_object($product)){
	return;
}
themify_product_image_start(); // Hook 
$hover_image = '';
if (isset($args['hover_image']) && 'yes' === $args['hover_image']) {
	$attachment_ids = $product->get_gallery_image_ids();
	if (!empty($attachment_ids) && is_array($attachment_ids)) {
		$hover_image = $attachment_ids[0];
	}
}
/* get product image for variable products */
$src = $product->get_image_id();
if (empty($src)) {
	$p = $product->get_parent_id();
	if (!empty($p)) {
		$parent_product = wc_get_product($p);
		if (!empty($parent_product)) {
			$src = $parent_product->get_image_id();
		}
		unset($parent_product, $p);
	}
	if (empty($src) && $args['fallback_s'] === 'yes' && $args['fallback_i'] !== '') {
		$src = $args['fallback_i'];
	}
}

$param_image = array(
	'w' => $args['image_w'],
	'h' => $args['image_h'],
	'src'=>$src
);

if ($args['link'] !== 'none') {
	$hasLink = true;
	$link = $args['link'] === 'permalink' ? get_the_permalink() : ($args['link'] === 'media' ? wp_get_attachment_url(get_post_thumbnail_id()) : '');
	$link_attr = Tbp_Utils::getLinkParams($args, $link);
	if (!isset($link_attr['href'])) {
		$hasLink = false;
	}
} else {
	$hasLink = false;
}
if ($args['sale_b'] === 'yes') {
	Tbp_Utils::loadCssModules('sale_badge', TBP_WC_CSS_MODULES . 'sale-badge.css');
}
if (!empty($args['appearance_image'])) {
	Tbp_Utils::loadCssModules('product-image', TBP_WC_CSS_MODULES . 'product-image.css');
}
?>
<figure class="product-image<?php echo isset($args['auto_fullwidth']) && $args['auto_fullwidth'] == '1' ? ' auto_fullwidth' : ''; ?><?php echo !empty($args['appearance_image']) ? ' image-wrap' : ''; ?><?php echo $args['sale_b'] === 'yes' ? ' sale-badge-' . $args['badge_pos'] : ''; ?>">
	<?php if ($args['sale_b'] === 'yes'): ?>
		<?php woocommerce_show_product_loop_sale_flash(); ?>
	<?php endif; ?>
	<?php if ($hasLink === true): ?>
		<a <?php echo themify_get_element_attributes($link_attr); ?>>
		<?php endif; ?>
		<?php echo apply_filters('woocommerce_product_get_image', themify_get_image($param_image), $product, '', array(), ''); ?>
		<?php
		if (!empty($hover_image)) {
			Tbp_Utils::loadCssModules('product-image-hover', TBP_WC_CSS_MODULES . 'product-image-hover.css');
			$param_image['src'] = $hover_image;
			$param_image['class'] = 'tbp_product_hover_image tf_abs tf_opacity';
			echo themify_get_image($param_image);
		}
		?>
		<?php if ($hasLink === true): ?>
		</a>
	<?php endif; ?>
</figure>
<?php
themify_product_image_end(); // Hook
$args = $param_image = null;
