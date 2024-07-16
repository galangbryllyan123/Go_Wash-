<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorBio extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'textarea', 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'Post Author Biography', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$user_id = get_post_field( 'post_author', get_the_ID() );
		return ! empty( $user_id )?get_user_meta( $user_id, 'description', true ):'';
	}
}
