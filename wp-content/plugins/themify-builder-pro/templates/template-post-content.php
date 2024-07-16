<?php
/**
 * Template Post Content
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
	'css' => '',
	'animation_effect' => '',
	'content_type' => 'full',
	'more_link' => '',
	'more_text' => __('Read More', 'tbp')
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']), $mod_name, $element_id, $fields_args);
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
	'class' => implode(' ', $container_class),
)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Post Content module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;

	if ( ! empty( $fields_args['p_bar'] ) && is_singular() && get_the_ID() === get_queried_object_id() && Themify_Builder::$frontedit_active === false) {
		Themify_Builder_Component_Module::add_modules_assets('tbprp', array(
			'selector' => '.tbp_read_bar',
			'js' => TBP_JS_MODULES . 'read-progress.js',
			'ver'=>TBP_VER
		));
		Themify_Enqueue_Assets::addPrefetchJs(TBP_JS_MODULES . 'read-progress.js',TBP_VER);
		?>
		<div class="tbp_read_bar tf_abs_t tf_w tf_hide"></div>
		<?php
	}
	global $page, $wp_query;
	if ($page > 1) {
		$fields_args['pro_paged']=$page;
	}
	if (is_main_query()) {
		$in_the_loop = in_the_loop();
		$wp_query->in_the_loop = true;
	}

	self::retrieve_template('partials/content.php', $fields_args, __DIR__);

	if (isset($in_the_loop)) {
		$wp_query->in_the_loop = $in_the_loop;
	}
	?>
</div>
<!-- /Post Content module -->