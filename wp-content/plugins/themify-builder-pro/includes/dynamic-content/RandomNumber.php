<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_RandomNumber extends Tbp_Dynamic_Item {
	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor','slider_range' );
	}

	public static function get_label():string {
		return __( 'Random Number', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$min = empty( $args['min'] ) ? 1 : $args['min'];
		$max = empty( $args['max'] ) ? 100 : $args['max'];
		return rand( $min, $max );
	}

	public static function get_options():array {
		return array(
			array(
				'label' => __( 'Minimum', 'tbp' ),
				'id' => 'min',
				'type' => 'text',
			),
			array(
				'label' => __( 'Maximum', 'tbp' ),
				'id' => 'max',
				'type' => 'text',
			),
		);
	}
}
