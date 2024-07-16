<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_Shortcode extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url', 'image', 'address' );
	}

	public static function get_label():string {
		return __( 'Shortcode', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		return !empty($args['shortcode'])?do_shortcode( $args['shortcode'] ):'';
	}

	public static function get_options():array {
		return array(
			array(
				'label' => __( 'Shortcode', 'tbp' ),
				'id' => 'shortcode',
				'type' => 'textarea'
			)
		);
	}
}
