<?php
/**
 * Template Archive Description
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

$element_id =$args['module_ID'];
$mod_name=$args['mod_name'];
$fields_args = $args['mod_settings']+ array(
	'css' => '',
	'format' => '',
	'animation_effect' => ''
);
$container_class = apply_filters( 'themify_builder_module_classes', array(
	'module',
	'module-' . $mod_name,
	$element_id,
	$fields_args['css']
), $mod_name, $element_id,$fields_args );
if(!empty($fields_args['global_styles']) && Themify_Builder::$frontedit_active===false){
    $container_class[] = $fields_args['global_styles'];
}
$container_props = apply_filters('themify_builder_module_container_props', self::parse_animation_effect($fields_args,array(
	'class' => implode(' ', $container_class),
)), $fields_args, $mod_name, $element_id);

if(Themify_Builder::$frontedit_active===false){
    $container_props['data-lazy']=1;
}
?>
<!-- Archive Description module -->
<div <?php echo themify_get_element_attributes(self::sticky_element_props($container_props,$fields_args)); ?>>
    <?php $container_props=$container_class=$args=null; 
        $description = is_author() ? get_the_author_meta( 'description',get_post_field( 'post_author', get_the_ID() ) ) : term_description();
		/** This filter is documented in wp-includes/general-template.php */
		$description = apply_filters( 'get_the_archive_description', $description );

		if ( $fields_args['format'] === 'y' ) {
			$description = apply_filters( 'themify_builder_module_content', $description );
		}

	    if ($description === '' && Themify_Builder::$frontedit_active===true) {
			$description = '<p>'.__( 'Archive description', 'tbp').'</p>';
	    }
	    echo $description;
    ?>
</div>
<!-- /Archive Description module -->
