<?php
/**
 * Template Comments
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
	'css' => '',
	'avatar' => 'y',
	'order' => '',
	'user_sort' => '',
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
<!-- Comments module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	if (Themify_Builder::$frontedit_active === true) {
		global $withcomments;
		$temp_withcomments = $withcomments;
		$withcomments = 1;
	}

	if ($fields_args['avatar'] !== 'y') {
		add_filter('get_avatar', ['TB_Comments_Module', 'return_false']);
	}

	$order = $fields_args['order'];
	if ($fields_args['user_sort'] === 'y' && isset($_GET['tbp_comment_order']) && in_array($_GET['tbp_comment_order'], ['asc', 'desc'], true)) {
		$order = $_GET['tbp_comment_order'];
	}
	elseif($order==='') {
		$order = get_option('comment_order', 'asc');
	}

	TB_Comments_Module::$comments_order = $order;
	add_filter('wp_list_comments_args', ['TB_Comments_Module', 'set_comments_order']);

	if ($fields_args['user_sort'] === 'y') :
		?>
		<form action="" method="get" class="tbp_comment_order">
			<select name="tbp_comment_order" onchange="javascript:this.closest('form').submit();">
				<option value="desc" <?php selected($order, 'desc'); ?>><?php _e('New Comments First', 'tbp'); ?></option>
				<option value="asc"<?php selected($order, 'asc'); ?>><?php _e('Old Comments First', 'tbp'); ?></option>
			</select>
		</form>
		<?php
	endif;

	comments_template();

	if ($fields_args['avatar'] !== 'y') {
		remove_filter('get_avatar', ['TB_Comments_Module', 'return_false']);
	}

	remove_filter('wp_list_comments_args', ['TB_Comments_Module', 'set_comments_order']);
	TB_Comments_Module::$comments_order = null;

	if (isset($temp_withcomments)) {
		$withcomments = $temp_withcomments;
		$temp_withcomments = null;
	}
	?>
</div>
<!-- /Comments module -->
