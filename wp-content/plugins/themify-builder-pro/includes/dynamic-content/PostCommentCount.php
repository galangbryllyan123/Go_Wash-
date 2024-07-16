<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostCommentCount extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor','slider_range' );
	}

	public static function get_label():string {
		return __( 'Comment Count', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		return get_comments_number( $args['post_id']??get_the_ID());
	}
}
