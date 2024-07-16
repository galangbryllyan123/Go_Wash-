<?php
/**
 * Template Search Form
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */
defined('ABSPATH') || exit; // Exit if accessed directly


$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+ array(
	'style' => 'form',
	'autocomplete' => '',
	'placeholder' => '',
	'button' => 'yes',
	'icon' => 'icon',
	'button_icon' => 'no',
	'button_t' => '',
	'button_i' => '',
	'css' => '',
	'animation_effect' => '',
	'post_type' => 'any',
	'search_tax' => 'category',
	'search_term' => '',
	'keywords' => '',
	'keywords_before' => ''
);
$isOverlay = 'overlay' === $fields_args['style'];
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'tf_search_form',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
	), $mod_name, $element_id, $fields_args);

if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
$container_class[] = $isOverlay === true ? 'tf_search_overlay' : 'tf_s_dropdown';
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);

if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
	$container_props['data-ajax'] = 'overlay' === $fields_args['style'] ? 'overlay' : ('on' === $fields_args['autocomplete'] ? 'dropdown' : false);
}
?>
<!-- Search Form module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	$url_params = [];
	if (!empty($fields_args['post_type']) && 'any' !== $fields_args['post_type']) {
		$url_params['post_type'] = $fields_args['post_type'];
		if (!empty($fields_args['search_term']) && $fields_args['search_term'] !== '0|single') {
			$url_params['tbp_s_tax'] = $fields_args['search_tax'];
			$url_params['tbp_s_term'] = urlencode($fields_args['search_term']);
		}
	}
	
	$icon = themify_get_icon('search', 'ti');
	do_action('pre_get_search_form');
	ob_start();
	?>
	<?php if ($isOverlay === true): ?>
		<div class="tf_search_icon tf_inline_b"><?php echo $icon; ?></div>
	<?php endif; ?>
    <form role="search" method="get" class="tbp_searchform<?php if ($isOverlay === true): ?> tf_hide<?php endif; ?><?php if ($fields_args['button_icon'] === 'yes'): ?> tf_rel<?php endif; ?>" action="<?php echo home_url() ?>">

        <?php if ( ! empty( $url_params ) ) : foreach ( $url_params as $key => $value ) : ?>
            <input type="hidden" name="<?php echo $key; ?>" value="<?php echo esc_attr( $value ); ?>" />
        <?php endforeach; endif; ?>

        <div class="tf_rel<?php if ($isOverlay === false): ?> tf_inline_b<?php endif; ?>">
			<?php if ($isOverlay === true || $fields_args['button_icon'] === 'yes'): ?>
				<div class="tf_icon_wrap"><?php echo $icon; ?></div>
			<?php endif; ?>
            <input type="text" name="s" title="<?php esc_attr_e($fields_args['placeholder']); ?>" placeholder="<?php esc_attr_e($fields_args['placeholder']); ?>" value="<?php echo get_search_query(); ?>"<?php if ($isOverlay === false || !empty($fields_args['keywords'])) : ?> autocomplete="off"<?php endif; ?>>

			<?php if (Themify_Builder::$frontedit_active === false && $fields_args['keywords']!=='') : ?>
				<div class="tbp_search_keywords tf_w tf_opacity tf_hidden tf_box tf_hide tf_scrollbar" tabindex="-1">
					<?php if ($fields_args['keywords_before']!=='') : ?>
						<div class="tbp_search_keywords_before"><?php echo $fields_args['keywords_before']; ?></div>
					<?php endif; ?>
					<?php
                    $base_url = add_query_arg($url_params, home_url('/'));
					$keywords = explode(',', $fields_args['keywords']);
					foreach ($keywords as $keyword) {
						$keyword = trim($keyword);
						echo '<a href="', add_query_arg(['s' => urlencode($keyword)], $base_url), '">', $keyword, '</a>';
					}
					?>
				</div>
			<?php endif; ?>
        </div>
		<?php if ($isOverlay === false && $fields_args['button'] === 'yes'): ?>
			<button type="submit" title="<?php esc_attr_e($fields_args['placeholder']); ?>">
				<div class="tbp_icon_search">
					<?php if ( $fields_args['icon'] === 'text' || $fields_args['icon'] === 'both' ) : echo '<span class="tbp_search_text">' . esc_attr($fields_args['button_t']) . '</span>'; endif; ?>
					<?php if ( $fields_args['icon'] === 'icon' || $fields_args['icon'] === 'both' ) : ?>
							<?php echo themify_get_icon( empty( $fields_args['button_i'] ) ? 'ti-search' : $fields_args['button_i'] ); ?>
					<?php endif; ?>
				</div>
			</button>
		<?php endif; ?>
    </form>
	<?php echo apply_filters('get_search_form', ob_get_clean(), []); /* documented in wp-includes/general-template.php */ ?>
</div><!-- /Search Form module -->
