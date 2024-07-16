<?php
global $product;
$isExist = isset($args['mod_name']) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true) ? false : null;
if(is_object($product)){
?>
<div class="product_meta">
	<?php do_action('woocommerce_product_meta_start'); ?>
	<?php if ($args['enable_sku'] === 'yes' && wc_product_sku_enabled() && ( ($sku = $product->get_sku()) || $product->is_type('variable') )) : ?>
		<span class="sku_wrapper">
			<?php if ($args['sku'] !== '') : ?>
				<span><?php echo $args['sku']; ?></span>: 
				<?php endif; ?>
				<?php
				if ($isExist === false) {
					$isExist = true;
				}
				?>
			<span class="sku"><?php echo $sku; ?></span>
		</span>
	<?php endif; ?>
	<?php
	if ($args['enable_cat'] === 'yes') {
		if ($args['cat'] !== '') {
			$args['cat'] = '<span>' . $args['cat'] . '</span>';
		}
		$output = tbp_get_terms_list( $product->get_id(), 'product_cat', [
			'sep' => isset( $args['cat_sep'] ) ? $args['cat_sep'] : ', ',
			'before' => '<span class="posted_in">' . $args['cat'],
			'after' => '</span>',
			'cover' => isset( $args['cat_c'] ) && 'yes' === $args['cat_c'],
			'cover_w' => isset( $args['cat_w'] ) ? $args['cat_w'] : '',
			'cover_h' => isset( $args['cat_h'] ) ? $args['cat_h'] : ''
		] );

		echo $output;
		if ($isExist === false) {
			$isExist = !empty($output);
		}
		$output = null;
	}
	if ($args['enable_tag'] === 'yes') {
		if ($args['tag'] !== '') {
			$args['tag'] = '<span>' . $args['tag'] . '</span>';
		}
		$output = tbp_get_terms_list( $product->get_id(), 'product_tag', [
			'sep' => isset( $args['tag_sep'] ) ? $args['tag_sep'] : ', ',
			'before' => '<span class="tagged_as">' . $args['tag'],
			'after' => '</span>',
			'cover' => isset( $args['tag_c'] ) && 'yes' === $args['tag_c'],
			'cover_w' => isset( $args['tag_w'] ) ? $args['tag_w'] : '',
			'cover_h' => isset( $args['tag_h'] ) ? $args['tag_h'] : ''
		] );

		echo $output;
		if ($isExist === false) {
			$isExist = !empty($output);
		}
		$output = null;
	}
	?>
	<?php do_action('woocommerce_product_meta_end'); ?>
</div>
<?php }
if ($isExist === false&& is_string($args['mod_name'])): ?>
	<div class="tbp_empty_module">
		<?php echo self::get_module_class($args['mod_name'])::get_module_name() ?>
	</div>
	<?php
endif;
$args = null;
