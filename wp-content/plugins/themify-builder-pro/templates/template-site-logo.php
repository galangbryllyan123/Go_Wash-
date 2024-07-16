<?php
/**
 * Template Site Logo
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
	'display' => 'text',
	'width_image' => '',
	'height_image' => '',
	'link' => 'siteurl',
	'custom_url' => '',
	'html_tag' => '',
	'url_image' => '',
	'css' => '',
	'animation_effect' => '',
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

if ('image' === $fields_args['display'] && '' !== $fields_args['url_image']) {
	$image = themify_get_image(array(
		'src' => esc_url($fields_args['url_image']),
		'w' => $fields_args['width_image'],
		'h' => $fields_args['height_image'],
        'title' => false,
        'alt' => get_bloginfo('name'),
		'image_size' => themify_builder_get('setting-global_feature_size', 'image_global_size_field'),
		'attr' => Themify_Builder::$frontedit_active === true ? array('data-w' => 'width_image', 'data-h' => 'height_image', 'data-name' => 'url_image') : false
	));
} else {
	$image = get_bloginfo('name');
}

$home_url = function_exists('themify_home_url') ? themify_home_url() : home_url(); /* backward compatibility, added Nov 2021 */
$url = 'siteurl' === $fields_args['link'] ? $home_url : ( 'custom' === $fields_args['link'] && '' !== $fields_args['custom_url'] ? esc_url($fields_args['custom_url']) : false);
if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Site Logo module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
    <div class="site-logo-inner">
		<?php $container_props = $container_class = $args = null;
		?>
		<?php if (!empty($fields_args['html_tag'])): ?>
			<<?php echo $fields_args['html_tag'] ?>>
		<?php endif; ?>

		<?php if ($url !== false): ?>
			<a href="<?php echo $url ?>">
			<?php endif; ?>

			<?php echo $image; ?>
			<?php if ($url !== false): ?>
			</a>
		<?php endif; ?>
		<?php if (!empty($fields_args['html_tag'])): ?>
			</<?php echo $fields_args['html_tag'] ?>>
		<?php endif; ?>
    </div>
</div>
<!-- /Site Logo module -->
