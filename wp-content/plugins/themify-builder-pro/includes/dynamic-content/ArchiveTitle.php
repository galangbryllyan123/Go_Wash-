<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ArchiveTitle extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Archive Title', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$value = single_term_title( '', false );
		} elseif ( is_author() ) {
			global $post;
			if(isset($post)){
				$value = get_the_author_meta( 'display_name', $post->post_author);
			}
		} elseif ( themify_is_shop() ) {
			$value = woocommerce_page_title( false );
		} elseif ( is_post_type_archive() ) {
			$value = post_type_archive_title( '', false );
		}

		return $value;
	}
}
