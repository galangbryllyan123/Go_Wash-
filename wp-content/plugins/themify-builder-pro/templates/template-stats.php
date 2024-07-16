<?php
/**
 * Template Statistics
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ [
	'tag' => 'ul',
	'sep' => '',
	's' => array(),
	'css' => ''
];
$container_class = apply_filters('themify_builder_module_classes', [
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
	], $mod_name, $element_id, $fields_args
);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, [
		'class' => implode(' ', $container_class),
	]), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- Statistics module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	$output = [];

	foreach ($fields_args['s'] as $stat) {
		$stat['val'] = empty($stat['val']) ? [] : $stat['val'];
		$item = '';

		if (!empty($stat['val']['icon'])) {
			$item .= themify_get_icon($stat['val']['icon']) . ' ';
		}
		if (!empty($stat['val']['before'])) {
			$item .= $stat['val']['before'];
		}

		switch ($stat['type']) :

			case 'text' :
				if (!empty($stat['val']['t'])) {
					$item .= do_shortcode($stat['val']['t']);
				}
				break;

			case 'posts' :
				$args = $stat['val']+[
					'post_type' => 'post'
				];
				$item .= wp_count_posts($args['post_type'])->publish;
				break;

			case 'comments' :
				$args = $stat['val']+[
					'status' => ''
				];
				$result = (array) wp_count_comments();
				if (empty($args['status'])) {
					$item .= $result['total_comments'];
				} else if (isset($result[$args['status']])) {
					$item .= $result[$args['status']];
				}
				break;

			case 'terms' :
				$args = $stat['val']+[
					'tax' => 'category',
					'hide_empty' => 0
				];
				$item .= wp_count_terms(['taxonomy' => $args['tax'], 'hide_empty' => $args['hide_empty']]);
				break;

			case 'users' :
				$args = $stat['val']+[
					'role' => ''
				];
				$result = count_users();
				if (empty($args['role'])) {
					$item .= $result['total_users'];
				} 
				elseif (isset($result['avail_roles'][$args['role']])) {
					$item .= $result['avail_roles'][$args['role']];
				}
				break;

		endswitch;

		if (!empty($stat['val']['after'])) {
			$item .= $stat['val']['after'];
		}

		$output[] = $item;
	}

	if ($fields_args['tag'] === 'ul' || $fields_args['tag'] === 'ol') {
		$wrap_start = "<{$fields_args['tag']}><li>";
		$separator = '</li><li>';
		$wrap_end = "</li></{$fields_args['tag']}>";
	} elseif ($fields_args['tag'] === 'p') {
		$wrap_start = '<p>';
		$separator = '</p><p>';
		$wrap_end = '</p>';
	} else {
		$wrap_start = '<span>';
		$separator = '</span>' . $fields_args['sep'] . '<span>';
		$wrap_end = '</span>';
	}

	echo $wrap_start, implode($separator, $output), $wrap_end;
	?>
</div><!-- /Statistics module -->
