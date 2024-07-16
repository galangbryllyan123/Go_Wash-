<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorName extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Post Author Name', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		return get_the_author_meta( 'display_name', get_post_field( 'post_author', get_the_ID() ) );
	}
}
