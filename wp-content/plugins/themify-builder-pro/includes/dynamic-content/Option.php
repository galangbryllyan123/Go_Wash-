<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_Option extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'advanced';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	public static function get_label():string {
		return __( 'Option', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = ! empty( $args['option_name'] )?get_option( $args['option_name'] ):'';
		return ! empty( $value )? $value : '';
	}

	public static function get_options():array {
		return array(
			array(
				'label' => __( 'Option Name', 'tbp' ),
				'id' => 'option_name',
				'type' => 'text',
				'help' => sprintf( __( 'You can see a list of options in <a href="%s" target="_blank">Options admin page</a>.', 'tbp' ), admin_url( 'options.php' ) )
			)
		);
	}
}
