<?php
/**
 * Template Post Navigation
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'labels' => 'yes',
	'prev_label' => '',
	'next_label' => '',
	'arrows' => 'yes',
	'prev_arrow' => '',
	'next_arrow' => '',
	'same_cat' => 'no',
	'tax' => 'category',
	'css' => '',
	'animation_effect' => ''
);
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
$same_cat = 'yes' === $fields_args['same_cat'];
?>
<!-- Post Navigation module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	$text = '';
	if ('yes' === $fields_args['arrows']) {
		$arrow = '' !== $fields_args['prev_arrow'] ? themify_get_icon($fields_args['prev_arrow']) : '&laquo;';
		$text = '<span class="tbp_post_navigation_arrow">' . $arrow . '</span>';
	}
	$label = 'yes' === $fields_args['labels'] ? $fields_args['prev_label'] : '';
	$navigate_label = '<span class="tbp_post_navigation_label">' . $label . '</span>';
	$previous_post_link = get_previous_post_link('%link', $text . '<span class="tbp_post_navigation_content_wrapper">' . $navigate_label . '<br><span class="tbp_post_navigation_title">%title</span></span>', $same_cat, '', $fields_args['tax']);
	echo $previous_post_link;

	$text = '';
	if ('yes' === $fields_args['arrows']) {
		$arrow = '' !== $fields_args['next_arrow'] ? themify_get_icon($fields_args['next_arrow']) : '&raquo;';
		$text = '<span class="tbp_post_navigation_arrow">' . $arrow . '</span>';
	}
	$label = 'yes' === $fields_args['labels'] ? $fields_args['next_label'] : '';
	$navigate_label = '<span class="tbp_post_navigation_label">' . $label . '</span>';
	$next_post_link = get_next_post_link('%link', $text . '<span class="tbp_post_navigation_content_wrapper">' . $navigate_label . '<br><span class="tbp_post_navigation_title">%title</span></span>', $same_cat, '', $fields_args['tax']);
	echo $next_post_link;
	?>

	<?php if (empty($previous_post_link) && empty($next_post_link) && is_string($mod_name) && ( Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true )) : ?>
		<div class="tbp_empty_module">
			<?php echo self::get_module_class($mod_name)::get_module_name() ?>
		</div>
	<?php endif; ?>

</div>
<!-- /Post Navigation module -->
