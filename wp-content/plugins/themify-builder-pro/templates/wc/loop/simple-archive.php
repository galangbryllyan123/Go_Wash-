<?php
global $product;
if (empty($args['archive_products']) || !is_object($product)) {
	return;
}
$archive_default = array(
	'image' => array(
		'on' => 1,
		'val' => array(
			'image_w' => '',
			'image_h' => '',
			'auto_fullwidth' => false,
			'appearance_image' => '',
			'sale_b' => 'yes',
			'badge_pos' => 'left',
			'fallback_s' => 'no',
			'fallback_i' => '',
			'lightbox_w_unit' => '%',
			'lightbox_h_unit' => '%',
			'link' => 'permalink',
			'open_link' => 'regular',
			'hover_image' => 'no'
		)
	),
	't' => array(
		'on' => '1',
		'val' => array(
			'link' => 'permalink',
			'open_link' => 'regular',
			'lightbox_w_unit' => '%',
			'lightbox_h_unit' => '%',
			'html_tag' => 'h2',
			'no_follow' => 'no'
		)
	),
	'p_meta' => array(
		'on' => 0,
		'val' => array(
			'enable_cat' => 'yes',
			'cat' => '',
			'enable_tag' => 'yes',
			'tag' => '',
			'enable_sku' => 'yes',
			'sku' => ''
		)
	),
	'p_desc' => array(
		'on' => 1,
		'val' => array(
			'description' => 'short'
		)
	),
	'p_price' => array(
		'on' => 1
	),
	'p_rating' => array(
		'on' => 0
	),
	'add_to_c' => array(
		'on' => 1,
		'val' => array(
			'quantity' => 'no'
		)
	)
);
$TBP_DIR = TBP_DIR . 'templates';
foreach ($args['archive_products'] as $item) {
	$id=$item['id'];
	if (isset($archive_default[$id])) {
		if (!isset($item['val'])) {
			$item['val'] = array();
		}
		if(isset($archive_default[$id]['val'])){
			$item['val']+=$archive_default[$id]['val'];
		}
		$on = isset($item['on']) ? ((int) $item['on']) : $archive_default[$id]['on'];
		if ($on !== 1) {
			continue;
		}
	} else {
		continue;
	}
	switch ($id) {
		// Title
		case 't':
			themify_product_title_start();
			self::retrieve_template('partials/title.php', $item['val'], $TBP_DIR);
			themify_product_title_end();
			break;

		// Description
		case 'p_desc':
			self::retrieve_template('wc/description.php', $item['val'], $TBP_DIR);
			break;
		// Meta
		case 'p_meta':
			self::retrieve_template('wc/meta.php', $item['val'], $TBP_DIR);
			break;
		// Image
		case 'image':
			self::retrieve_template('wc/loop/image.php', $item['val'], $TBP_DIR);
			break;
		// Product Price
		case 'p_price':
				themify_product_price_start(); // Hook 
				?>
				<div class="post-meta entry-meta tbp_post_meta product-price">
					<?php self::retrieve_template('wc/price.php', $item['val'], $TBP_DIR); ?>
				</div>
				<?php
				themify_product_price_end(); // Hook
			break;
		// Rating
		case 'p_rating':
			self::retrieve_template('wc/rating.php', $item['val'], $TBP_DIR);
			break;

		// Add To Cart
		case 'add_to_c':
			self::retrieve_template('wc/loop/add-to-cart.php', $item['val'], $TBP_DIR);
			break;
	}
}
$args = null;
