<?php
/**
 * Template Product Image
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
if (!defined('ABSPATH')  || !themify_is_woocommerce_active()){
	return;
}
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'thumb_image' => 'thumb_img_bottom',
	'image_w' => '',
	'image_h' => '',
	'auto_fullwidth' => false,
	'appearance_image' => '',
	'sale_b' => 'yes',
	'badge_pos' => 'left',
	'link' => 'permalink',
	'open_link' => 'regular',
	'fallback_s' => 'no',
	'fallback_i' => '',
	'zoom' => 'yes',
	'css' => '',
	'animation_effect' => ''
);
if (!empty($fields_args['appearance_image'])) {
	$fields_args['appearance_image'] = self::get_checkbox_data($fields_args['appearance_image']);
}
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css'],
	$fields_args['appearance_image'],
	$fields_args['thumb_image']
	), $mod_name, $element_id, $fields_args);
if(isset(Themify_Builder::$is_loop)){
	$isLoop=Themify_Builder::$is_loop;
}
else{//backward
	global $ThemifyBuilder;
	$isLoop = $ThemifyBuilder->in_the_loop === true;
}
if ($isLoop === false) {
	if ($fields_args['auto_fullwidth'] == '1') {
		$container_class[] = ' auto_fullwidth';
	}
	$container_class[] = $fields_args['appearance_image'];
}
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
if ('no' === $fields_args['zoom']) {
	$container_class[] = 'tbp_disable_wc_zoom';
} else {
	add_filter('themify_theme_product_gallery_type', array('TB_Product_Image_Module', 'product_gallery_type'));
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);


if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Product Image module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	$fields_args['element_id'] = $element_id;
	if ($isLoop === true) {
		self::retrieve_template('wc/loop/image.php', $fields_args, __DIR__);
	} else {
		self::retrieve_template('wc/single/image.php', $fields_args, __DIR__);
	}
	?>
</div>
<!-- /Product Image module -->
