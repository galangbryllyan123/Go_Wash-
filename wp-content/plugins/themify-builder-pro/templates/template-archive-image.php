<?php
/**
 * Template Archive Cover Image
 *
 * Access original fields: $args['mod_settings']
 * @author Themify
 */

defined('ABSPATH') || exit; // Exit if accessed directly

/* spoof Image module */
$args['mod_settings']['css_image'] = 'module-' . $args['mod_name'];
$args['mod_name'] = 'image';

/* disable inline editor */
if ( is_category() || is_tag() || is_tax() ) {
	$cat = get_queried_object();
	$value = get_term_meta( $cat->term_id, 'tbp_cover', true );
	if(empty($value) && themify_is_woocommerce_active() && is_product_category()){
	    $value=get_term_meta($cat->term_id, 'thumbnail_id', true);
	    if(!empty($value)){
			$value=wp_get_attachment_url( $value);
	    }
	}
	
	$args['mod_settings']['url_image'] = $value;
	$args['mod_settings']['title_image'] = single_term_title( '', false );
} elseif ( Themify_Builder::$frontedit_active===true || is_singular( Tbp_Templates::SLUG ) ) {
	$args['mod_settings']['url_image'] = THEMIFY_BUILDER_URI . '/img/image-placeholder.png';
	$args['mod_settings']['title_image'] = __( 'Archive Title', 'tbp' );
}

self::retrieve_template( 'template-image.php', $args ,THEMIFY_BUILDER_TEMPLATES_DIR);

$args=null;