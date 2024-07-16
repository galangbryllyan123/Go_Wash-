<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */

class Tbp_Dynamic_Item_PTBDate extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb' ,false);
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'date' );
	}

	public static function get_label():string {
		return __( 'PTB (Date)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		$args = wp_parse_args( $args, array(
			'show' => 'start_date',
		) );

		if ( ! empty( $args['field'] ) ) {
			list( $post_type, $field_name ) = explode( ':', $args['field'] );
			$value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );

			// 'range' date fields
			if ( is_array( $value ) ) {
				$value = $value[ $args['show'] ];
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'event_date' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'start_date' => 'startat',
					'end_date' => 'endat'
				),
			),
		);
	}
}