<?php
/**
 * Template Read Time
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

global $more;
$mod_name = $args['mod_name'];
$element_id = $args['module_ID'];
$fields_args = $args['mod_settings']+array(
	'm_t' => '',
	'ic' => '',
	'less' => '',
	'b_text' => '',
	'a_text' => '',
	'css' => '',
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
<!-- Post Read Time -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props, $fields_args)); ?>>
	<?php
	$container_props = $container_class = $args = null;
	echo Themify_Builder_Component_Module::get_module_title($fields_args, 'm_t');
	$read_time = TB_Readtime_Module::get_word_count(get_the_ID());
	?>
	<div class="tbp_rd_tm<?php if($read_time===0):?> tf_hide<?php endif;?>"<?php if($read_time===0):?> data-rtid="<?php echo get_the_ID()?>"<?php endif;?>>
		<?php if ($fields_args['ic']!==''):?>
			<span class="tbp_rd_tm_ic tf_inline_b"><?php echo themify_get_icon($fields_args['ic'])?></span>
		<?php endif;?>
		<?php if ($fields_args['less']!=='' && $read_time<=1):?>
			<span class="tbp_rd_tm_less"><?php echo $fields_args['less']?></span>
		<?php endif;?>
		<?php if ($read_time===0 || $read_time>1):?>
			<?php if($fields_args['b_text']!==''):?>
				<span class="tbp_rd_tm_b"><?php echo $fields_args['b_text']?></span>
			<?php endif;?>
			<?php if($read_time>0):?>
				<span><?php echo $read_time ?></span>
			<?php endif;?>
			<?php if($fields_args['a_text']!==''):?>
				<span class="tbp_rd_tm_a"><?php echo $fields_args['a_text']?></span>
			<?php endif;?>
		<?php endif;?>
	</div>
</div>