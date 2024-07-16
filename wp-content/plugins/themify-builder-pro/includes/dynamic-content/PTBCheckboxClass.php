<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBCheckboxClass extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb' ,false);
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'custom_css' );
	}

	public static function get_label():string {
		return __( 'PTB (Checkbox)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';

		if ( ! empty( $args['field'] ) ) {
			$field_name = explode( ':', $args['field'] );
            
			$meta_value = get_post_meta( get_the_ID(), 'ptb_' . $field_name[1], true );
            if ( ! empty( $meta_value ) ) {
                $value = join( ' ', array_filter( $meta_value ) );
            }
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
			)
		);
	}
}