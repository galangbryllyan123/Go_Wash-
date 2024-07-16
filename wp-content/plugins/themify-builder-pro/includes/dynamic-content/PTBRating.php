<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRating extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'PTB (Rating)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		if ( empty( $args['field'] ) || ! function_exists( 'ptb_get_field' ) ) {
			return '';
		}

		$field_name = explode( ':', $args['field'] )[1];

		/* the option names translate directly to PTB's option names for the field */
		$args = wp_parse_args( $args, array(
			'icon' => 'fa-star',
			'size' => 'small',
			'vcolor' => 'rgba(250, 225, 80, 1)',
		) );
		return ptb_get_field( $field_name, $args );
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'rating' ),
			),
			array(
				'label' => 'size',
				'id' => 'size',
				'type' => 'select',
				'options' => array(
					'small' => 'sml',
					'medium' => 'mdm',
					'large' => 'lrg'
				),
			),
			array(
				'id' => 'icon',
				'type' => 'icon',
				'label' => 'icon'
			),
			array(
				'id' => 'vcolor',
				'type' => 'color',
				'label' => 'c'
			),
		);
	}
}