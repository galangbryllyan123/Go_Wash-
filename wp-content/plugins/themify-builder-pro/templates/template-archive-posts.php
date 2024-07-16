<?php
/**
 * Template Archive Posts
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$builder_id = $args['builder_id'];
$fields_args = $args['mod_settings']+ array(
	'layout_post' => 'grid3',
	'masonry' => 'no',
	'no_found' => '',
	'per_page' => get_option('posts_per_page'),
	'pagination' => 'yes',
	'pagination_option' => 'numbers',
	'next_link' => '',
	'prev_link' => '',
	'tab_content_archive_posts' => array(),
	'css' => '',
	'animation_effect' => '',
	'offset' => '',
	'order' => 'DESC',
	'orderby' => 'date',
	// static query in APP
	'post_type' => 'post',
	'term_type' => 'category',
	'tax' => 'category',
	'terms' => '',
	'slug' => '',
	'display' => 'grid',
	// Slider
	'visible_opt_slider' => '',
	'mob_visible_opt_slider' => '',
	'tab_visible_opt_slider' => '',
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
	$fields_args['layout_post'] = '';
}
$is_builder_active=Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true;
$is_ajax_filter = isset($_POST['action']) && $_POST['action'] === 'themify_ajax_load_more';
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	isset($appearance_image) ? 'module-image ' . $appearance_image : ''
), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && $is_builder_active===false) {
	$container_class[] = $fields_args['global_styles'];
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
		$fields_args['orderby'] = sanitize_text_field($_POST['orderby']);
	}
}

$per_page = (int) $fields_args['per_page'];

if ($isSlider === false && isset($fields_args['post_filter']) && $fields_args['post_filter'] === 'yes' && function_exists('themify_masonry_filter')) {
	$filter_args = array(
		'query_category' => '0',
		'el_id' => $element_id
	);
	if (isset($fields_args['filter_hashtag']) && $fields_args['filter_hashtag'] === 'yes') {
		$filter_args['hash_tag'] = true;
	}
	if (isset($fields_args['ajax_filter']) && $fields_args['ajax_filter'] === 'yes') {
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
			$filter_args['ajax_sort_order'] = $fields_args['order'];
			$filter_args['ajax_sort_order_by'] = $fields_args['orderby'];
		}
	}
	$fields_args['masonry'] = 'yes';
}
if (isset($fields_args['builder_content'])) {
	$fields_args['builder_id'] = $builder_id;
	unset($fields_args['tab_content_archive_posts']);
	$isAPP = true;
	if (is_string($fields_args['builder_content'])) {
		$fields_args['builder_content'] = json_decode($fields_args['builder_content'], true);
	}
	$container_class[] = 'themify_builder_content-' . str_replace('tb_', '', $element_id);
} 
else {
	Tbp_Utils::convert_toggleable_fields($fields_args['tab_content_archive_posts']);
	foreach($fields_args['tab_content_archive_posts'] as $v){
		if($v['id']==='image'){
			if(!empty($v['val']['appearance_image'])){
				$container_class[] ='module-image ' . self::get_checkbox_data($v['val']['appearance_image']);
			}
			break;
		}
	}
	$isAPP = null;
}

$query_args = array(
	'post_type' => $fields_args['post_type'],
	'post_status' => 'publish',
	'ptb_disable' => true,
	'order' => $fields_args['order'],
	'orderby' => $fields_args['orderby'] === 'id'?'ID':$fields_args['orderby'],
	'posts_per_page' => $per_page,
	'paged' => $paged,
	'offset' => (($paged - 1) * $per_page),
);
if (false !== ($id = get_the_ID()) && (Themify_Builder::$is_loop===true || is_singular())) {
	$query_args['post__not_in'] = array($id);
}
if ($fields_args['offset'] !== '') {
	$query_args['offset'] += (int) $fields_args['offset'];
}

/* backward compatibility, since Sep 2022 */
if ( $query_args['orderby'] === 'meta_value_num' ) {
	$query_args['orderby'] = 'meta_value';
	$fields_args['meta_key_type'] = 'NUMERIC';
}
/* end backward compatibility */

if ( ! empty( $fields_args['meta_key'] ) && $query_args['orderby'] === 'meta_value' ) {
	$query_args['meta_key'] = $fields_args['meta_key'];
	if ( ! empty( $fields_args['meta_key_type'] ) ) {
		$query_args['meta_type'] = $fields_args['meta_key_type'];
	}
}

if($isAPP===true){
	TB_Advanced_Posts_Module::get_query($query_args,$fields_args);
}
elseif('related-posts' === $mod_name){
	TB_Related_Posts_Module::get_query($query_args,$fields_args);
}
else{
	TB_Archive_Posts_Module::get_query($query_args,$fields_args);
}

if (true === $is_ajax_filter && isset($_POST['tax'])) {

	/* fix post filter in Archive Posts module where no post type/taxonomy selection exists */
	if ( empty( $fields_args['tax'] ) && isset( $_POST['taxonomy'] ) ) {
		$fields_args['tax'] = sanitize_text_field( $_POST['taxonomy'] );
	}

	$query_args['tax_query'] = array(
		array(
			'taxonomy' => $fields_args['tax'],
			'field' => 'term_id',
			'terms' => (int) $_POST['tax'],
			'operator' => 'IN'
		)
	);
}

$query_args=apply_filters('tbp_archive_posts_query', $query_args, $fields_args);
if (isset($filter_args) && isset($filter_args['ajax_filter'])) {
	/* in Ajax post filters, disable some query args */
	unset($query_args['post__in']);
}
elseif(isset($query_args['post__in'])){
	unset($query_args['post__not_in']);
}
$the_query = new WP_Query($query_args);

if ( ! $the_query->have_posts() && $fields_args['hide_empty'] === 'yes' && $is_builder_active === false) {
	return;
}

$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
	'class' => implode(' ', $container_class),
)), $fields_args, $mod_name, $element_id);

set_query_var('tf_query_tax', $fields_args['tax'] );
if (isset($filter_args) && isset($filter_args['ajax_filter'])) {
	set_query_var('tf_ajax_filter', true);
}
if ($is_builder_active=== false) {
	$container_props['data-lazy'] = 1;
}
$post_type=$query_args['post_type'];

if(isset(Themify_Builder::$is_loop)){
	$isLoop=Themify_Builder::$is_loop;
	Themify_Builder::$is_loop=true;
}
else{//backward
	global $ThemifyBuilder;
	$isLoop = $ThemifyBuilder->in_the_loop === true;
	$ThemifyBuilder->in_the_loop = true;
}
?>
<!-- <?php echo $mod_name ?> module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class =$query_args = $args = null;
	if ($the_query->have_posts()) :

		global $post;
		if ( isset( $post ) ){
			$saved_post = clone $post;
		}
		$class = array('builder-posts-wrap', 'loops-wrapper');
		if ($post_type !== 'post') {
			$class[] = implode(' ', (array) $post_type);
		}

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
				'data-speed' => $fields_args['speed_opt_slider'] === 'slow' ? 4 : ($fields_args['speed_opt_slider'] === 'fast' ? '.5' : 1),
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
		} else {
			if ($fields_args['masonry'] === 'yes') {
				$class[] = 'tbp_masonry';
				$class[] = 'masonry';
			} else {
				unset($filter_args);
			}
		}

		$class[] = apply_filters('themify_builder_module_loops_wrapper', $fields_args['layout_post'], $fields_args, $mod_name); //deprecated backward compatibility
		$class = apply_filters('themify_loops_wrapper_class', $class, $post_type, $fields_args['layout_post'], 'builder', $fields_args, $mod_name);

		$class[] = 'tf_clear tf_clearfix';
		$container_props = array();
		if ( isset( $fields_args['masonry_align'] ) && 'yes' === $fields_args['masonry_align'] && in_array('masonry', $class, true)) {
			$container_props['data-layout'] = 'fitRows';
		}
		$container_props['class'] = implode(' ', $class);

		if ($is_builder_active === false) {
			$container_props['data-lazy'] = 1;
		}
		if ('ajax' === $fields_args['pagination_option']) {
			$container_props['class'] .= ' tb_ajax_pagination';
			$container_props['data-id'] = $element_id;
		}
		unset($class);

		Tbp_Utils::disable_ptb_loop();
		if (isset($filter_args)) {
			global $themify;
			if (isset($themify)) {
				$oldVal=$themify->post_filter;
				$themify->post_filter = 'yes';
			}
			$filter_args['query_taxonomy'] = $fields_args['tax'];
			themify_masonry_filter($filter_args);
			unset($filter_args);
			if (isset($oldVal)) {
				$themify->post_filter =$oldVal;
				unset($oldVal);
			}
		}
		?>

		<?php if (!empty($fields_args['heading'])): ?>
			<h2><?php echo $fields_args['heading']; ?></h2>
			<?php endif; ?>

		<div <?php echo themify_get_element_attributes( $container_props ); unset( $container_props ); ?>>

			<?php if ($isSlider === true) : ?>
				<div class="themify_builder_slider tf_carousel tf_swiper-container tf_rel tf_overflow"<?php if (Tbp_Utils::$isActive === false) : ?> data-lazy="1"<?php endif; ?><?php echo themify_get_element_attributes($container_inner); ?>>
					<div class="tf_swiper-wrapper tf_lazy tf_rel tf_w tf_h tf_textc">
			<?php endif; ?>

			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

				<?php if ($isSlider === true) : ?>
					<div class="tf_swiper-slide">
						<div class="slide-inner-wrap"<?php if (!empty($margin)) : ?> style="<?php echo $margin; ?>"<?php endif; ?>>
				<?php endif; ?>

				<?php themify_post_before(); // hook   ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('post tf_clearfix'); ?>>
					<?php
					themify_post_start(); // hook
					if ($isAPP === true) {
						self::retrieve_template('partials/advanched-archive.php', $fields_args,__DIR__);
					} else {
						self::retrieve_template('partials/simple-archive.php', $fields_args,__DIR__);
					}
					themify_post_end(); // hook
					?>
				</article>

				<?php themify_post_after(); // hook  ?>

				<?php if ($isSlider === true) : ?>
						</div>
					</div><!-- .tf_swiper-slide -->
				<?php endif; ?>

			<?php
			endwhile;

			/* reset query back to original */
			if ( isset( $saved_post )) {
				$post = $saved_post;
				setup_postdata( $saved_post );
				unset($saved_post);
			}
			?>
			<?php if ($isSlider === true) : ?>
					</div><!-- .tf_swiper-wrapper -->
				</div><!-- .themify_builder_slider -->
			<?php endif; ?>

		</div><!-- .builder-posts-wrap -->

		<?php
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
		?>
	<?php else: ?>
		<?php echo $fields_args['no_found']; ?>
	<?php endif; ?>

</div><!-- /<?php echo $mod_name ?> module -->
<?php
if(isset(Themify_Builder::$is_loop)){
	Themify_Builder::$is_loop=$isLoop;
}
else{//backward
	$ThemifyBuilder->in_the_loop = $isLoop;
}