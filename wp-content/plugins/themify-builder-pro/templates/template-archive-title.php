<?php
/**
 * Template Archive Title
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
	'html_tag' => 'h2',
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
?>
<!-- Archive Title module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	if (is_search()) {
		$title = sprintf(__('Search Results for: %s', 'tbp'), esc_html(get_search_query(false)));
	} elseif (is_date()) {
		$title = get_the_archive_title();
	} elseif (is_home()) {
		$title = __('Latest Posts', 'tbp');
	} elseif (is_author()) {
		$title = '<span class="vcard">' . get_the_author_meta( 'display_name', get_post_field( 'post_author', get_the_ID() ) ) . '</span>';
	} elseif (themify_is_shop()) {
		$title = woocommerce_page_title(false);
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} else {
		$title = single_term_title('', false);
	}
	$isEmpty = empty($title) && (Tbp_Utils::$isActive === true || Themify_Builder::$frontedit_active === true || Tbp_Public::$isTemplatePage === true);
	?>
    <<?php echo $fields_args['html_tag'] ?><?php if ($isEmpty === true): ?> class="tbp_empty_module"<?php endif; ?>><?php echo $isEmpty === true &&  is_string($mod_name)?  self::get_module_class($mod_name)::get_module_name() : $title ?></<?php echo $fields_args['html_tag'] ?>>
</div>
<!-- /Archive Title module -->
