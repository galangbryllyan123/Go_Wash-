<?php
/**
 * Template Archive Products
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

if (!defined('ABSPATH') || !themify_is_woocommerce_active()){
	return;
}
$is_builder_active=Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true;
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$builder_id = $args['builder_id'];
$fields_args = $args['mod_settings']+array(
	'layout_product' => 'grid3',
	'masonry' => 'no',
	'orderby' => 'date',
	'order' => 'DESC',
	'sort' => 'no',
	'per_page' => get_option('posts_per_page'),
	'pagination' => 'yes',
	'pagination_option' => 'numbers',
	'next_link' => '',
	'prev_link' => '',
	'no_found' => '',
	'offset' => '',
	'archive_products' => array(),
	'css' => '',
	'animation_effect' => '',
	'query_type' => 'product_cat',
	'term_type' => 'category',
	'terms' => '',
	'tag_products' => '', 
	'category_products' => '', /* deprecated */
	'display' => 'grid',
	// Slider
	'visible_opt_slider' => '',
	'tab_visible_opt_slider' => '',
	'mob_visible_opt_slider' => '',
	'auto_scroll_opt_slider' => 0,
	'scroll_opt_slider' => '',
	'speed_opt_slider' => '',
	'effect_slider' => 'scroll',
	'pause_on_hover_slider' => 'resume',
	'play_pause_control' => 'no',
	'wrap_slider' => 'yes',
	'show_nav_slider' => 'yes',
	'show_arrow_slider' => 'yes',
	'show_arrow_buttons_vertical' => '',
	'left_margin_slider' => '',
	'right_margin_slider' => '',
	'height_slider' => 'variable',
	'hide_empty' => 'no'
);
$isSlider = $fields_args['display'] === 'slider';
if ($isSlider === true) {
	$fields_args['layout_product'] = '';
}

$per_page = (int) $fields_args['per_page'];
$is_ajax_filter = isset($_POST['action']) && $_POST['action'] === 'themify_ajax_load_more';
$ajax_filter_enabled = false;

$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'woocommerce',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
		), $mod_name, $element_id, $fields_args);

if (isset($fields_args['builder_content'])) {
	$fields_args['builder_id'] = $builder_id;
	unset($fields_args['archive_products']);
	$isAPP = true;
	if (is_string($fields_args['builder_content'])) {
		$fields_args['builder_content'] = json_decode($fields_args['builder_content'], true);
	}
	$container_class[] = 'themify_builder_content-' . str_replace('tb_', '', $element_id);
} else {

	Tbp_Utils::convert_toggleable_fields($fields_args['archive_products']);
	foreach($fields_args['archive_products'] as $v){
		if($v['id']==='image'){
			if(!empty($v['val']['appearance_image'])){
				$container_class[] = 'module-product-image ' . self::get_checkbox_data($v['val']['appearance_image']);
			}
			break;
		}
	}
	$isAPP = null;
}

if (true === $is_ajax_filter && isset($_POST['page'])) {
	$paged = (int) $_POST['page'];
} else {
	$paged = $fields_args['pagination'] === 'yes' || $fields_args['pagination'] === 'on' ? self::get_paged_query() : 1;
}
if (true === $is_ajax_filter) {
	if (isset($_POST['order'])) {
		$fields_args['order'] = sanitize_text_field($_POST['order']);
	}
	if (isset($_POST['orderby'])) {
		if ('rate' === $_POST['orderby']) {
			$top_rated = true;
		} else {
			$fields_args['orderby'] = sanitize_text_field($_POST['orderby']);
		}
	}
}
if ($isSlider === false && !empty($fields_args['post_filter']) && $fields_args['post_filter'] !== 'no' && function_exists('themify_masonry_filter')) {

	$ajax_filter_enabled = isset($fields_args['ajax_filter']) && $fields_args['ajax_filter'] === 'yes';
	$filter_args = array(
		'query_category' => '0',
		'el_id' => $element_id
	);
	if (isset($fields_args['filter_hashtag']) && $fields_args['filter_hashtag'] === 'yes') {
		$filter_args['hash_tag'] = true;
	}
	if ( $ajax_filter_enabled ===true) {
		$filter_args['ajax_filter'] = 'yes';
		$filter_args['ajax_filter_id'] = $builder_id;
		$filter_args['ajax_filter_paged'] = $paged;
		$filter_args['ajax_filter_limit'] = $per_page;
		$fields_args['pagination_option'] = 'ajax';
		$cat_filter = isset($fields_args['ajax_filter_categories']) ? $fields_args['ajax_filter_categories'] : 'exclude';
		if(('exclude' === $cat_filter || 'include' === $cat_filter) && !empty($fields_args['ajax_filter_'.$cat_filter])) {
			$filter_args['ajax_filter_'.$cat_filter]=sanitize_text_field($fields_args['ajax_filter_'.$cat_filter]);
		}
		if (isset($fields_args['ajax_sort']) && $fields_args['ajax_sort'] === 'yes') {
			$filter_args['ajax_sort'] = 'yes';
			$filter_args['ajax_filter_wc'] = true;
			$filter_args['ajax_sort_order'] = $fields_args['order'];
			$filter_args['ajax_sort_order_by'] = $fields_args['orderby'];
		}
	}
	$fields_args['masonry'] = 'yes';
}
$query_args = array(
	'post_type' => 'product',
	'post_status' => 'publish',
	'ptb_disable' => true,
	'posts_per_page' => $per_page,
	'paged' => $paged,
	'order' => $fields_args['order'],
	'orderby' => array($fields_args['orderby'] => $fields_args['order']),
	'offset' => ( ( $paged - 1 ) * $per_page ),
    'tbp_aap' => true, // flag used by WPF plugin
);
if ($fields_args['offset'] !== '') {
	$query_args['offset'] += (int) $fields_args['offset'];
}
if ($fields_args['orderby'] === 'price') {
    add_filter( 'posts_join', [ ( $isAPP === true ? 'TB_Advanced_Products_Module' : 'TB_Archive_Products_Module' ), 'join_filter' ] );
    add_filter( 'posts_orderby', [ ( $isAPP === true ? 'TB_Advanced_Products_Module' : 'TB_Archive_Products_Module' ), "orderby_{$query_args['order']}_filter" ] );
} elseif ($fields_args['orderby'] === 'sales') {
	$query_args['meta_query'][$fields_args['orderby']] = array(
		'key' => 'total_sales',
		'type' => 'NUMERIC'
	);
}
if (isset($top_rated)) {
	$query_args['orderby']['top_rated'] = $query_args['order'];
	$query_args['meta_query']['top_rated'] = array(
		'key' => '_wc_average_rating',
		'type' => 'NUMERIC'
	);
}
if ( $ajax_filter_enabled===false && $fields_args['sort'] === 'yes' && !empty($_GET['orderby'])) {
	$ordering_args = WC()->query->get_catalog_ordering_args();
	$query_args['orderby'] = $ordering_args['orderby'];
	$query_args['order'] = $ordering_args['order'];
	if ($ordering_args['meta_key']) {
		$query_args['meta_key'] = $ordering_args['meta_key'];
	}
}

if($isAPP===true){
	TB_Advanced_Products_Module::get_query($query_args,$fields_args);
}
else{
	TB_Archive_Products_Module::get_query($query_args,$fields_args);
}
$query_taxonomy = $fields_args['query_type'];
if (true === $is_ajax_filter && isset($_POST['tax'])) {
	$query_args['tax_query'] = array(array(
			'taxonomy' => $query_taxonomy,
			'field' => 'term_id',
			'terms' => (int) $_POST['tax'],
			'operator' => 'IN'
	));
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
			'class' => implode(' ', $container_class),
		)), $fields_args, $mod_name, $element_id);

if(!isset($query_args['post__in']) && false !== ($id = get_the_ID()) && (Themify_Builder::$is_loop===true || is_singular($id))){
	if(isset($query_args['post__not_in'])){
		$query_args['post__not_in'] = array($id);
	}else{
		$query_args['post__not_in'][]=$id;
	}
}

$the_query = new WP_Query(apply_filters('tbp_archive_products_query', $query_args, $fields_args));
if ( $fields_args['orderby'] === 'price' ) {
    remove_filter( 'posts_join', [ ( $isAPP === true ? 'TB_Advanced_Products_Module' : 'TB_Archive_Products_Module' ), 'join_filter' ] );
    remove_filter( 'posts_orderby', [ ( $isAPP === true ? 'TB_Advanced_Products_Module' : 'TB_Archive_Products_Module' ), "orderby_{$query_args['order']}_filter" ] );
}

if ($fields_args['hide_empty'] === 'yes' && $is_builder_active === false &&  !$the_query->have_posts() ) {
	return;
}

wc_set_loop_prop( 'total', $the_query->found_posts );

if(isset(Themify_Builder::$is_loop)){
	$isLoop=Themify_Builder::$is_loop;
	Themify_Builder::$is_loop=true;
}
else{//backward
	global $ThemifyBuilder;
	$isLoop = $ThemifyBuilder->in_the_loop === true;
	$ThemifyBuilder->in_the_loop = true;
}
if ($is_builder_active === false) {
	$container_props['data-lazy'] = 1;
}

?>
<!-- <?php echo $mod_name ?> module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $query_args = $args = null;
	if ($the_query->have_posts()) {

		global $post;
		if ( isset( $post ) ){
			$saved_post = clone $post;
		}
		$class = array('builder-posts-wrap', 'loops-wrapper', 'tbp_posts_wrap', 'products');
		if ($isSlider === true) {
			$margin = '';
			if ($fields_args['left_margin_slider'] !== '') {
				$margin = 'margin-left:' . $fields_args['left_margin_slider'] . 'px;';
			}
			if ($fields_args['right_margin_slider'] !== '') {
				$margin .= 'margin-right:' . $fields_args['right_margin_slider'] . 'px';
			}
			$container_inner = array(
				'data-visible' => $fields_args['visible_opt_slider'],
				'data-tab-visible' => $fields_args['tab_visible_opt_slider'],
				'data-mob-visible' => $fields_args['mob_visible_opt_slider'],
				'data-scroll' => $fields_args['scroll_opt_slider'],
				'data-speed' => $fields_args['speed_opt_slider'] === 'slow' ? 4 : ($fields_args['speed_opt_slider'] === 'fast' ? .5 : 1),
				'data-wrapvar' => $fields_args['wrap_slider'] !== 'no' ? 1 : 0,
				'data-slider_nav' => $fields_args['show_arrow_slider'] === 'yes' ? 1 : 0,
				'data-pager' => $fields_args['show_nav_slider'] === 'yes' ? 1 : 0,
				'data-effect' => $fields_args['effect_slider'],
				'data-height' => $fields_args['height_slider'],
			);

			if ($container_inner['data-slider_nav'] === 1 && $fields_args['show_arrow_buttons_vertical'] === 'vertical') {
				$container_inner['data-nav_out'] = 1;
				$container_inner['data-css_url'] = THEMIFY_BUILDER_CSS_MODULES .'sliders/carousel.css';
				$class[] = ' themify_builder_slider_vertical';
			}
			if ($fields_args['auto_scroll_opt_slider'] && $fields_args['auto_scroll_opt_slider'] !== 'off') {
				$container_inner['data-auto'] = $fields_args['auto_scroll_opt_slider'] * 1000;
				$container_inner['data-pause_hover'] = $fields_args['pause_on_hover_slider'] === 'resume' ? 1 : 0;
				$container_inner['data-controller'] = $fields_args['play_pause_control'] === 'yes' ? 1 : 0;
			}
			$wrap_tag = 'div';
			$item_tag = 'div';
		} else {
			$wrap_tag = 'ul';
			$item_tag = 'li';
			if ($fields_args['sort'] === 'yes') {
				woocommerce_catalog_ordering();
			}
		}

		if ($fields_args['masonry'] === 'yes') {
			$class[] = 'tbp_masonry';
			$class[] = 'masonry';
		} else {
			unset($filter_args);
		}
		$class[] = apply_filters('themify_builder_module_loops_wrapper', $fields_args['layout_product'], $fields_args, $mod_name); //deprecated backward compatibility
		$class = apply_filters('themify_loops_wrapper_class', $class, 'product', $fields_args['layout_product'], 'builder', $fields_args, $mod_name);
		$class[] = 'tf_clear tf_clearfix';

		$container_props = apply_filters('themify_builder_blog_container_props', array(
			'class' => $class
				), 'product', $fields_args['layout_product'], $fields_args, $mod_name);
		if ('ajax' === $fields_args['pagination_option']) {
			$container_props['class'][] = 'tb_ajax_pagination';
			$container_props['data-id'] = $element_id;
		}
		if ($is_builder_active === false) {
			$container_props['data-lazy'] = 1;
		}
		if (isset($fields_args['masonry_align']) && 'yes' === $fields_args['masonry_align'] && in_array('masonry', $container_props['class'])) {
			$container_props['data-layout'] = 'fitRows';
		}
		$container_props['class'] = implode(' ', $container_props['class']);
		unset($class);

		Tbp_Utils::disable_ptb_loop();
		if (isset($filter_args)) {
			global $themify;
			if (isset($themify)) {
				$oldVal=$themify->post_filter;
				$themify->post_filter = 'yes';
			}
			$filter_args['query_taxonomy'] = $query_taxonomy;
			themify_masonry_filter($filter_args);
			if (isset($oldVal)) {
				$themify->post_filter =$oldVal;
				unset($oldVal);
			}
		}
		$edit_link_enabled = function_exists( 'themify_post_edit_link' );
		?>
		<<?php echo $wrap_tag, themify_get_element_attributes($container_props); ?>>
		<?php unset($container_props); ?>

		<?php if ($isSlider === true) : ?>
			<div class="themify_builder_slider tf_carousel tf_swiper-container tf_rel tf_overflow"<?php if ($is_builder_active === false) : ?> data-lazy="1"<?php endif; ?><?php echo themify_get_element_attributes($container_inner); ?>>
				<div class="tf_swiper-wrapper tf_lazy tf_rel tf_w tf_h tf_textc">
		<?php endif; ?>

				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
					<?php if ($isSlider === true) : ?>
						<div class="tf_swiper-slide">
							<div class="slide-inner-wrap"<?php if (!empty($margin)) : ?> style="<?php echo $margin; ?>"<?php endif; ?>>
							<?php endif; ?>
							<?php
							$classes = 'post tf_clearfix';
							if (isset($filter_args)) {
								$categories = wp_get_object_terms(get_the_ID(), $query_taxonomy);
								foreach ($categories as $category) {
									$classes .= ' cat-' . $category->term_id;
								}
								if (isset($filter_args['ajax_filter'])) {
									if (true === $is_ajax_filter && isset($_POST['tax'])) {
										$classes .= ' ajax-cat-'.$_POST['tax'];
									} else {
										$classes .= ' initial-cat';
									}
								}
							}
							?>
							<<?php echo $item_tag; ?> id="post-<?php the_ID(); ?>" <?php wc_product_class($classes); ?>>

							<?php 
							do_action('tbp_before_shop_loop_item');
							if ( $edit_link_enabled===true ){
								themify_post_edit_link();
							}
							if ($isAPP === true) {
								self::retrieve_template('partials/advanched-archive.php', $fields_args,__DIR__);
							} else {
								self::retrieve_template('wc/loop/simple-archive.php', $fields_args,__DIR__);
							}
							do_action('tbp_after_shop_loop_item'); 
							?>

							</<?php echo $item_tag; ?>>

							<?php if ($isSlider === true) : ?>
							</div>
						</div><!-- .tf_swiper-slide -->
					<?php endif; ?>
				<?php endwhile; ?>

				<?php if ($isSlider === true) : ?>
				</div><!-- .tf_swiper-wrapper -->
			</div><!-- .themify_builder_slider -->
		<?php endif; ?>

		</<?php echo $wrap_tag; ?>>
		<?php
		unset($filter_args);
		/* reset query back to original */
		if ( isset( $saved_post ) ) {
			$post = $saved_post;
			setup_postdata( $saved_post );
			unset($saved_post);
		}
		if ($fields_args['display'] === 'grid' && $fields_args['pagination'] === 'yes') {
			$page_args = array(
				'pagination_option' => $fields_args['pagination_option'],
				'query' => $the_query,
			);
			if ('ajax' === $fields_args['pagination_option']) {
				$page_args['paged'] = $paged;
				$page_args['el_id'] = $element_id;
			} else {
				$page_args['next_link'] = $fields_args['next_link'];
				$page_args['prev_link'] = $fields_args['prev_link'];
			}
			self::retrieve_template('partials/pagination.php', $page_args,__DIR__);
		}
	}
	else{
		echo $fields_args['no_found'];
	} ?>
	<!-- /<?php echo $mod_name ?> module -->
</div>
<?php
if(isset(Themify_Builder::$is_loop)){
	Themify_Builder::$is_loop=$isLoop;
}
else{//backward
	$ThemifyBuilder->in_the_loop = $isLoop;
}