<?php
/**
 * Template Maps Pro
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined( 'ABSPATH' ) || exit;

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$marker_defaults = array(
	'title' => '',
	'address' => '',
	'image' => ''
);
$fields_args = $args['mod_settings']+array(
	'mod_title' => '',
	'map_link' => '',
	'map_center' => '',
	'zoom_map' => 4,
	'w_map' => '',
	'w_map_unit' => '',
	'h_map' => 350,
	'type_map' => 'ROADMAP',
	'scrollwheel_map' => '',
	'draggable_map' => 'enable',
	'disable_map_ui' => '',
	'map_polyline' => '',
	'map_polyline_geodesic' => '',
	'map_polyline_stroke' => 2,
	'map_polyline_color' => '',
	'map_display_type' => 'dynamic',
	'w_map_static' => 500,
	'animation_effect' => '',
	'style_map' => '',
	'display' => 'text',
	'trigger' => '',
	'css_class' => '',
);
if ($fields_args['map_display_type'] !== 'dynamic' && $fields_args['display'] === 'posts') {
	$fields_args['map_display_type'] = 'dynamic';
}
if ($fields_args['w_map_unit'] === '') {
	$fields_args['w_map_unit'] = isset($fields_args['unit_w']) ? $fields_args['unit_w'] : 'px';
}
if (empty($fields_args['w_map'])) {
	$fields_args['w_map'] = 100;
	$fields_args['w_map_unit'] = '%';
}
$container_class = apply_filters('themify_builder_module_classes', array(
	'module', 'module-' . $mod_name, $element_id, $fields_args['css_class']
	), $mod_name, $element_id, $fields_args);
if (Themify_Builder::$frontedit_active === false) {
	if (!empty($fields_args['global_styles'])) {
		$container_class[] = $fields_args['global_styles'];
	}
	$container_class[] = 'tf_lazy';
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'id' => $element_id,
		'class' => implode(' ', $container_class),
		'data-zoom' => $fields_args['zoom_map'],
		'data-type' => $fields_args['type_map'],
		'data-address' => $fields_args['map_center'],
		'data-scrollwheel' => $fields_args['scrollwheel_map'] === 'enable' ? '1' : '',
		'data-draggable' => $fields_args['draggable_map'],
		'data-disable_map_ui' => $fields_args['disable_map_ui'] === 'yes' ? '1' : '',
		'data-polyline' => $fields_args['map_polyline'] === 'yes' ? '1' : '',
		'data-geodesic' => $fields_args['map_polyline_geodesic'] === 'no' ? '1' : '',
		'data-polylineStroke' => $fields_args['map_polyline_stroke'],
		'data-polylineColor' => $fields_args['map_polyline_color'],
		'data-trigger' => $fields_args['trigger'] === 'hover' ? '1' : '',
	)), $fields_args, $mod_name, $element_id);

if ('' !== $fields_args['style_map'] && $fields_args['map_display_type'] === 'dynamic') {
	$container_props['data-style_map'] = $fields_args['style_map'];
}


if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
?>
<!-- module maps pro -->
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$map_options = $container_props = $container_class = $args=null;

	if (method_exists('Themify_Builder_Component_Module', 'get_module_title')) {
		echo Themify_Builder_Component_Module::get_module_title($fields_args, 'mod_title');
	} elseif ($fields_args['mod_title'] !== '') {
		echo $fields_args['before_title'], apply_filters('themify_builder_module_title', $fields_args['mod_title'], $fields_args), $fields_args['after_title'];
	}
	do_action('themify_builder_before_template_content_render');
	?>

	<?php if ($fields_args['map_display_type'] === 'dynamic') : ?>
		<div class="maps-pro-canvas-container">
			<div class="maps-pro-canvas" style="width:<?php echo $fields_args['w_map'], $fields_args['w_map_unit']; ?>;height:<?php echo $fields_args['h_map'] ?>px"></div>
		</div>
		<?php
		$markers = $fields_args['display'] === 'posts' ? TB_Maps_Pro_Module::get_post_markers($fields_args) : ( isset($fields_args['markers']) ? $fields_args['markers'] : [] );
		if (!empty($markers)):?>
			<template>
				<?php foreach ($markers as $marker) :
					$marker+=$marker_defaults;?>
					<div data-address="<?php echo !empty($marker['latlng']) ? $marker['latlng'] : $marker['address'] ?>" data-image="<?php echo $marker['image']; ?>">
						<?php echo do_shortcode(TB_Maps_Pro_Module::sanitize_text($marker['title'])); ?>
					</div>
				<?php endforeach; ?>
			</template>
		<?php endif; ?>
		<?php
	else :

		$queryArgs = '';
		if ($fields_args['map_center'] !== '') {
			$queryArgs = 'center=' . $fields_args['map_center'];
		}
		$queryArgs .= '&zoom=' . $fields_args['zoom_map'];
		$queryArgs .= '&maptype=' . strtolower($fields_args['type_map']);
		$queryArgs .= '&size=' . preg_replace('/[^0-9]/', '', $fields_args['w_map_static']) . 'x' . preg_replace('/[^0-9]/', '', $fields_args['h_map']);
		$queryArgs .= '&key=' . Themify_Builder_Model::getMapKey();

		/* markers */
		if (!empty($markers)) {
			foreach ($markers as $marker) {
				$marker+=$marker_defaults;
				if ($marker['image']==='') {
					$queryArgs .= '&markers=' . urlencode($marker['address']);
				} else {
					$queryArgs .= '&markers=icon:' . urlencode($marker['image']) . '%7C' . urlencode($marker['address']);
				}
			}
		}
		$marker_defaults = null;
		/* Map style */
		if ('' !== $fields_args['style_map']) {
			$style = Builder_Maps_Pro::get_map_style($fields_args['style_map']);
			foreach ($style as $rule) {
				$queryArgs .= '&style=';
				if (isset($rule['featureType'])) {
					$queryArgs .= 'feature:' . $rule['featureType'] . '%7C';
				}
				if (isset($rule['elementType'])) {
					$queryArgs .= 'element:' . $rule['elementType'] . '%7C';
				}
				if (isset($rule['stylers'])) {
					foreach ($rule['stylers'] as $styler) {
						foreach ($styler as $prop => $value) {
							$value = str_replace('#', '0x', $value);
							$queryArgs .= $prop . ':' . $value . '%7C';
						}
					}
				}
			}
		}

		if ('gmaps' === $fields_args['map_link'] && !empty($fields_args['map_center'])){
			echo '<a href="http://maps.google.com/?q=' . esc_attr($fields_args['map_center']) . '" target="_blank" rel="nofollow">';
		}
		?>
		<img src="//maps.googleapis.com/maps/api/staticmap?<?php echo $queryArgs; ?>">
		<?php
		if ('gmaps' === $fields_args['map_link'] && !empty($fields_args['map_center'])){
			echo '</a>';
		}
		?>

	<?php endif; ?>

	<?php do_action('themify_builder_after_template_content_render'); ?>
</div>
<!-- /module maps pro -->