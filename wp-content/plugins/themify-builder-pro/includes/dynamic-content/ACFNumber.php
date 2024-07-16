<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFNumber extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_acf' ,false);
	}

	public static function get_category():string {
		return 'acf';
	}

	public static function get_type():array {
		return array( 'text', 'range', 'textarea', 'wp_editor','slider_range' );
	}

	public static function get_label():string {
		return __( 'ACF (Number)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		return !empty( $args['key'] )?Themify_Builder_Plugin_Compat_acf::get_field_value( $args ):'';
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'key',
				'type' => 'select',
				'dataset' => 'acf_fields',
				'dataset_args' => [ 'type' => 'number' ],
			),
		);
	}
}
