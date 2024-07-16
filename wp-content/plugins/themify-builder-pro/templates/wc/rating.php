<?php
global $product;
if(!is_object($product)){
	return;
}
?>
<div class="woocommerce-product-rating">
	<?php
		$rating_count = $product->get_rating_count();
		if ($rating_count > 0) {
			?>
			<?php
			echo woocommerce_template_loop_rating();
			if (Tbp_Public::$is_archive === false && !is_product_category() && !is_product_tag()):
				?>
				<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf(__('%s customer review', 'tbp'), $rating_count); ?>)</a>
			<?php endif; ?>
			<?php
		} 
		elseif (isset($args['always_show']) && $args['always_show'] === 'y') {
			$review_count = $product->get_review_count();
			?>
			<div class="star-rating" role="img" aria-label="<?php _e('Rated 0 out of 5', 'tbp'); ?>">
				<?php echo wc_get_star_rating_html(0, 0) ?>
			</div>
			<?php if (comments_open()) : ?>
				<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf(_n('%s customer review', '%s customer reviews', $review_count, 'tbp'), '<span class="count">' . esc_html($review_count) . '</span>'); ?>)</a>
			<?php endif; ?>
			<?php
		} elseif (isset($args['mod_name']) && is_string($args['mod_name']) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true)) {
			?>
			<div class="tbp_empty_module">
				<?php echo self::get_module_class($args['mod_name'])::get_module_name() ?>
			</div>
			<?php
		}
		$args = null;
	?>
</div>