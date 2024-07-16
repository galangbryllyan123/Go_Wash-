<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBCheckbox extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb' ,false);
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_label():string {
		return __( 'PTB (Checkbox)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';

		if ( ! empty( $args['field'] ) && function_exists( 'ptb_get_field' ) ) {
			$field_name = explode( ':', $args['field'] );
			$value= ptb_get_field( $field_name[1], [
				'seperator' => $args['sep'] ?? ''
			] );
		}

		return $value;

	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'checkbox' ),
			),
			array(
				'label' => 'sep',
				'id' => 'sep',
				'type' => 'text',
				'help' => __( 'Character to separate items when multiple options are selected.', 'tbp' ),
			),
		);
	}
}