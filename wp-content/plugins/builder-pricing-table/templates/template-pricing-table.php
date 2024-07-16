<?php
/**
 * Template Pricing Table
 * 
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined( 'ABSPATH' ) || exit;

$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
	'mod_appearance_pricing_table' => '',
	'mod_pricing_blank_button' => '',
	'mod_color_pricing_table' => 'tb_default_color',
	'mod_enlarge_pricing_table' => '',
	'mod_title_pricing_table' => '',
	'mod_title_icon_pricing_table' => '',
	'mod_price_pricing_table' => '',
	'mod_recurring_fee_pricing_table' => '',
	'mod_description_pricing_table' => '',
	'mod_feature_list_pricing_table' => '',
	'mod_unavailable_feature_list_pricing_table' => '',
	'mod_button_text_pricing_table' => '',
	'mod_button_link_pricing_table' => '',
	'mod_pop_text_pricing_table' => '',
	'animation_effect' => '',
	'css_pricing_table' => ''
);
if (!empty($fields_args['mod_appearance_pricing_table'])) {
	$fields_args['mod_appearance_pricing_table'] = self::get_checkbox_data($fields_args['mod_appearance_pricing_table']);
}
if ($fields_args['mod_color_pricing_table'] === 'default') {
	$fields_args['mod_color_pricing_table'] = 'tb_default_color';
}
$container_class = apply_filters('themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	'ui',
	$element_id,
	$fields_args['mod_appearance_pricing_table'],
	$fields_args['mod_color_pricing_table'],
	$fields_args['css_pricing_table'],
	'tf_textc tf_rel tf_w'
	), $mod_name, $element_id, $fields_args);
if (!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active === false) {
	$container_class[] = $fields_args['global_styles'];
}
if ($fields_args['mod_enlarge_pricing_table'] === 'enlarge') {
	$container_class[] = 'pricing-enlarge';
}
if ($fields_args['mod_pop_text_pricing_table'] !== '') {
	$container_class[] = 'pricing-pop';
}
$feature_list = explode("\n", $fields_args['mod_feature_list_pricing_table']);
$unavailable_feature_list = explode("\n", $fields_args['mod_unavailable_feature_list_pricing_table']);
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args, array(
		'id' => $element_id,
		'class' => implode(' ', $container_class),
	)), $fields_args, $mod_name, $element_id);
if (Themify_Builder::$frontedit_active === false) {
	$container_props['data-lazy'] = 1;
}
if (method_exists('Themify_Builder_Model', 'load_color_css')) {
	if ($fields_args['mod_appearance_pricing_table'] !== '') {
		Themify_Builder_Model::load_appearance_css($fields_args['mod_appearance_pricing_table']);
	}
	Themify_Builder_Model::load_color_css($fields_args['mod_color_pricing_table']);
}
?>
<div <?php echo self::get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php $container_props = $container_class = $args = null; ?>
	<?php do_action('themify_builder_before_template_content_render'); ?>

	<?php if ($fields_args['mod_pop_text_pricing_table'] !== ''): ?>
		<span class="module-pricing-table-pop tf_textc tf_w"><?php echo $fields_args['mod_pop_text_pricing_table']; ?></span>
	<?php endif; ?>

    <div class="module-pricing-table-header tf_rel ui <?php echo $fields_args['mod_color_pricing_table'] ?><?php if ($fields_args['mod_appearance_pricing_table'] !== ''): ?> <?php echo $fields_args['mod_appearance_pricing_table'] ?><?php endif; ?>" >
		<?php if ($fields_args['mod_title_pricing_table'] !== ''): ?>
			<span class="module-pricing-table-title tf_block tf_w">
				<?php if ($fields_args['mod_title_icon_pricing_table'] !== ''): ?>
					<span><?php echo themify_get_icon($fields_args['mod_title_icon_pricing_table']); ?></span>
				<?php endif; ?>
				<span><?php echo $fields_args['mod_title_pricing_table']; ?></span>
			</span>
		<?php endif; ?>
		<?php if ($fields_args['mod_price_pricing_table'] !== ''): ?>
			<span class="module-pricing-table-price tf_block tf_w"><?php echo $fields_args['mod_price_pricing_table']; ?></span>
		<?php endif; ?>
		<?php if ($fields_args['mod_recurring_fee_pricing_table'] !== ''): ?>
			<p class="module-pricing-table-reccuring-fee"><?php echo $fields_args['mod_recurring_fee_pricing_table']; ?></p>
		<?php endif; ?>
		<?php if ($fields_args['mod_description_pricing_table'] !== ''): ?>
			<p class="module-pricing-table-description"><?php echo $fields_args['mod_description_pricing_table']; ?></p>
		<?php endif; ?>
    </div><!-- .module-pricing-table-header -->
    <div class="module-pricing-table-content tf_box tf_w">
		<?php if (!empty($feature_list)): ?>
			<?php foreach ($feature_list as $line): ?>
				<?php if(!empty($line)):?>
					<div class="module-pricing-table-features"><?php echo apply_filters('themify_builder_module_content', $line); ?></div>
				<?php endif;?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (!empty($unavailable_feature_list)): ?>
			<?php foreach ($unavailable_feature_list as $line): ?>
				<?php if(!empty($line)):?>
					<div class="module-pricing-table-features unavailable-features"><?php echo apply_filters('themify_builder_module_content', $line); ?></div>
				<?php endif;?>
			<?php endforeach ?>
		<?php endif; ?>
		<?php if ($fields_args['mod_button_text_pricing_table'] !== ''): ?>
			<?php
			$class = array($fields_args['mod_color_pricing_table']);
			if ($fields_args['mod_pricing_blank_button'] === 'modal') {
				$class[] = 'lightbox-builder themify_lightbox';
			}
			if ($fields_args['mod_appearance_pricing_table']) {
				$class[] = $fields_args['mod_appearance_pricing_table'];
			}
			?>
			<a class="module-pricing-table-button tf_rel ui <?php echo implode(' ', $class) ?>" href="<?php echo $fields_args['mod_button_link_pricing_table'] !== '' ? $fields_args['mod_button_link_pricing_table'] : '#' ?>"<?php if ($fields_args['mod_pricing_blank_button'] === 'external'): ?> target="_blank"<?php endif; ?>>
				<?php echo $fields_args['mod_button_text_pricing_table']; ?> 
			</a> 
		<?php endif; ?>
    </div><!-- .module-pricing-table-content -->
	<?php do_action('themify_builder_after_template_content_render'); ?>
</div><!-- /module pricing-table -->
