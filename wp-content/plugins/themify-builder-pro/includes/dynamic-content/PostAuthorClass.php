<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorClass extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'custom_css' );
	}

	public static function get_label():string {
		return __( 'Post Author ID', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		$user_id = get_post_field( 'post_author', get_the_ID() );
		if ( ! empty( $user_id ) ) {
			$value = 'author-' . $user_id;
		}

		return $value;
	}
}
