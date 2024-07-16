<?php
/**
 * Template Product Taxonomy
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+  array(
	'tax' => 'category',
	'cols' => 3,
	'link' => 'yes',
	'count' => '',
	'img_size' => '',
	'img_w' => '',
	'img_h' => '',
	'desc' => 'yes',
	'c_img' => 'yes',
	'search' => '',
	'limit' => '',
	'paginate' => 'no',
	'top' => '',
	'exclude' => '',
	'orderby' => 'id',
	'order' => 'DESC',
	'empty' => false,
	'display' => 'post',
	'post_n' => 3,
	'sub_n' => '',
	'css' => '',
	'animation_effect' => ''
);
$display = $fields_args['display'];

$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	'loops-wrapper',
	'tbp_tax_' . $display,
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args);

$layout = is_numeric($fields_args['cols']) ? ($fields_args['cols'] > 1 ? 'grid' . $fields_args['cols'] : 'list-post') : $fields_args['cols'];
$container_class = apply_filters('themify_loops_wrapper_class', $container_class, 'taxonomy', $layout, 'builder', $fields_args, $mod_name);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
$showCount = $fields_args['count'] === 'yes';
$showLink = $fields_args['link'] === 'yes';
$showDesc = $fields_args['desc'] === 'yes';
$query_args = array(
	'taxonomy' => $fields_args['tax'],
	'orderby' => $fields_args['orderby'],
	'order' => $fields_args['order'],
	'hide_empty' => $fields_args['empty'] === 'yes',
	'pad_counts' => $showCount,
	'search' => $fields_args['search'],
	'number' => $fields_args['limit'],
	'exclude' => $fields_args['exclude'],
	'parent' => $fields_args['top'] === 'yes' ? 0 : ''
);
$paginate = $fields_args['paginate'] === 'yes';
?>
<!-- Product Taxonomy module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
    if ( $paginate ) {
        $total = get_terms( $query_args + [ 'fields' => 'count' ] );
        $current_page = isset( $_GET['tbp_tax_page'] ) ? (int) $_GET['tbp_tax_page'] : 1;
        $query_args['offset'] = ( $current_page - 1 ) * $query_args['number'];
        $total_pages = ceil( $total / $query_args['number'] );
    }
	$taxes = get_terms($query_args);
	$container_props = $container_class = $query_args = $args = $layout = null;
	if (method_exists('Themify_Builder_Component_Module', 'get_module_title')) {
		echo Themify_Builder_Component_Module::get_module_title($fields_args, 'm_t');
	} elseif ($fields_args['m_t'] !== '') {
		echo $fields_args['before_title'], apply_filters('themify_builder_module_title', $fields_args['m_t'], $fields_args), $fields_args['after_title'];
	}
	global $wp_taxonomies;

	$preset = $fields_args['img_size'] !== '' ? $fields_args['img_size'] : themify_builder_get('setting-global_feature_size', 'image_global_size_field');
	$param_image = array('w' => $fields_args['img_w'], 'h' => $fields_args['img_h'], 'image_size' => $preset, 'class' => 'tbp_tax_cover');
	if (Themify_Builder::$frontedit_active === true) {
		$param_image['attr'] = array('data-w' => 'img_w', 'data-h' => 'img_h');
	}
	$isPTBEnable = class_exists('PTB',false);
	do_action('themify_builder_before_template_content_render');
	?>
	<?php if (!empty($taxes) && !is_wp_error($taxes)): ?>
		<?php foreach ($taxes as $tax) : ?>
			<?php if (isset($wp_taxonomies[$tax->taxonomy])): ?>
				<?php $link = $showLink === true ? get_term_link($tax) : null; ?>
				<div class="post">
					<?php if ($fields_args['c_img'] === 'yes'): ?>
						<?php
						$thumb_id = get_term_meta($tax->term_id, 'tbp_cover', true);
						$isPlaceholder = false;
						if (empty($thumb_id)) {
							if ($isPTBEnable === true) {
								$thumb_id = get_term_meta($tax->term_id, 'ptb_term_cover', true);
								if (!empty($thumb_id)) {
									$thumb_id = $thumb_id[0];
								}
							}
							if ($tax->taxonomy === 'product_cat' && empty($thumb_id) && themify_is_woocommerce_active()) {
								$thumb_id = get_term_meta($tax->term_id, 'thumbnail_id', true);
								if (empty($thumb_id)) {
									$thumb_id = wc_placeholder_img_src();
									$isPlaceholder = true;
								}
							}
						}
						if (!is_numeric($thumb_id) && $isPlaceholder === false) {
							$thumb_id = themify_get_attachment_id_cache($thumb_id);
						}
						if (!empty($thumb_id)) {
							$param_image['src'] = $thumb_id;
							$img = themify_get_image($param_image);
							if ($img !== '') {
								?>
								<?php if ($link !== null): ?>
									<a href="<?php echo $link ?>">
								<?php endif; ?>
									<?php echo $img ?>
								<?php if ($link !== null): ?>
									</a>
								<?php
								endif;
							}
						}
						?>
					<?php endif; ?>

					<h3>
						<?php if ($link !== null): ?>
							<a href="<?php echo $link ?>">
							<?php endif; ?>
							<?php echo $tax->name ?>
							<?php if ($showCount === true): ?>
								<span class="tbp_tax_count">(<?php echo $tax->count ?>)</span>
							<?php endif; ?>
							<?php if ($link !== null): ?>
							</a>
						<?php endif; ?>
					</h3>
					<?php if ($showDesc === true && $tax->description !== ''): ?>
						<p class="tbp_tax_desc"><?php echo $tax->description ?></p>
					<?php endif; ?>

					<?php
					if ($display !== 'n') {
						if ($display === 'post') {
							$query = get_posts(
								array(
									'post_type' => $wp_taxonomies[$tax->taxonomy]->object_type,
									'numberposts' => $fields_args['post_n'],
									'tax_query' => array(
										array(
											'taxonomy' => $tax->taxonomy,
											'field' => 'term_id',
											'terms' => $tax->term_id
										)
									),
									'order' => 'DESC',
									'orderby' => 'date',
									'no_found_rows' => true
								)
							);
						} else {
							$query = get_terms(array(
								'taxonomy' => $tax->taxonomy,
								'orderby' => $fields_args['orderby'],
								'order' => $fields_args['order'],
								'hide_empty' => $fields_args['empty'] === 'yes',
								'pad_counts' => $showCount,
								'no_found_rows' => true,
								'number' => $fields_args['sub_n'],
								'parent' => $tax->term_id
							));
						}
						if (!empty($query) && !is_wp_error($query)):
							?>
							<div class="tbp_tax_childs">
								<?php foreach ($query as $post): ?>
									<div class="tbp_tax_item">
										<?php if ($display === 'post'): ?>
											<?php $img = get_the_post_thumbnail($post, 'thumbnail'); ?>
											<?php if ($img): ?>
												<?php if ($showLink === true): ?>
													<a href="<?php echo get_the_permalink($post) ?>">
													<?php endif; ?>
													<?php echo $img ?>
													<?php if ($showLink === true): ?>
													</a>
												<?php endif; ?>
											<?php endif; ?>
										<?php else: ?>
											<h3>
												<?php if ($showLink === true): ?>
													<a href="<?php echo get_term_link($post); ?>">
													<?php endif; ?>
													<?php echo $post->name ?>
													<?php if ($showCount === true): ?>
														<mark class="tbp_tax_count">(<?php echo $post->count ?>)</mark>
													<?php endif; ?>
													<?php if ($showLink === true): ?>
													</a>
												<?php endif; ?>
											</h3>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php } ?>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>

        <?php
        if ( $paginate ) {
            echo self::get_pagination( '', '', 'tbp_tax_page', 0, $total_pages, $current_page );
        }
        ?>

	<?php endif ?>
	<?php do_action('themify_builder_after_template_content_render');
	unset($param_image, $isPTBEnable);
	?>
</div>
<!-- /Product Taxonomy module -->
