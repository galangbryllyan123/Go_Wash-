<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostClass extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'custom_css' );
	}

	public static function get_label():string {
		return __( 'Post CSS Class', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		return implode( ' ', get_post_class( '', $args['post_id']?? get_the_ID()) );
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'pstid',
				'id' => 'post_id',
				'type' => 'number',
				'help' => 'tbp_pstidh'
			),
		);
	}
}
