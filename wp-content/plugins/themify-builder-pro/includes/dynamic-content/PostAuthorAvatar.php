<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorAvatar extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'image' );
	}

	public static function get_label():string {
		return __( 'Post Author Avatar', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$size = isset( $args['size'] ) ? (int) $args['size'] : 96;
		$user_id = get_post_field( 'post_author', get_the_ID() );
		return get_avatar_url( $user_id, array( 'size' => $size ) );
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'size',
				'id' => 'size',
				'type' => 'number',
				'class'=>'large',
				'help' => __( 'Height and width of the avatar in pixels. Default is 96.', 'tbp' )
			),
		);
	}
}
