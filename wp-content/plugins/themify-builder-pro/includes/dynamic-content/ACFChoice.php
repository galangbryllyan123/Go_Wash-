<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFChoice extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_acf',false );
	}

	public static function get_category():string {
		return 'acf';
	}

	public static function get_label():string {
		return __( 'ACF (Choice Fields)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$sep = isset( $args['sep'] ) ? $args['sep'] : ',';
			$field_value = (array) Themify_Builder_Plugin_Compat_acf::get_field_value( $args );
			$value = implode( $sep, $field_value );
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' =>'tbp_f',
				'id' => 'key',
				'type' => 'select',
				'dataset' => 'acf_fields',
				'dataset_args' => [ 'type' => [ 'select', 'checkbox', 'radio', 'button_group', 'true_false' ] ],
			),
			array(
				'label' => 'sep',
				'id' => 'sep',
				'type' => 'text',
				'help' => __( 'Character to separate items when multiple choice is allowed.', 'tbp' ),
			),
		);
	}
}
