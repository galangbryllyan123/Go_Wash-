<?php
/**
 * Template Site Tagline
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'link' => '',
	'html_tag' => 'div',
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

if ($fields_args['html_tag'] === '') {
	$fields_args['html_tag'] = 'div';
}
if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Site Tagline module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php $container_props = $container_class = $args = null; ?>
	<<?php echo $fields_args['html_tag'] ?><?php if ('' === $fields_args['link']): ?> class="tbp_site_tagline_heading"<?php endif; ?>>

	<?php if ('' !== $fields_args['link']): ?>
		<a class="tbp_site_tagline_heading" href="<?php echo esc_url($fields_args['link']) ?>">
		<?php endif; ?>

		<?php echo get_bloginfo('description'); ?>

		<?php if ('' !== $fields_args['link']): ?>
		</a>
	<?php endif; ?>

	</<?php echo $fields_args['html_tag'] ?>>
</div>
<!-- /Site Tagline module -->
