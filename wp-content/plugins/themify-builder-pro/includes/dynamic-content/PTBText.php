<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBText extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	public static function get_label():string {
		return __( 'PTB (Text)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$sep = $args['sep']?? ',';
			$field_name = explode( ':', $args['field'] )[1];
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( is_array( $value ) ) {
				if ( PTB_Repeater_Field::$in_the_loop ) {
					$n = PTB_Repeater_Field::$repeater_index;
					$value = isset( $value[ $n ] ) ? $value[ $n ] : '';
				} else {
					$value = array_filter( $value );
					// repeatable Text fields
					$value = ! empty( $value ) ? implode( $sep, $value ) : '';
				}
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'text' ),
			),
			array(
				'label' => 'sep',
				'id' => 'sep',
				'type' => 'text',
				'help' => __( 'Character to separate items in repeatable Text fields.', 'tbp' ),
			),
		);
	}
}