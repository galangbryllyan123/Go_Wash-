<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRatingAsText extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'slider_range' );
	}

	public static function get_label():string {
		return __( 'PTB (Rating)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) ? $args['show'] : 'total';
			$field_name = explode( ':', $args['field'] )[1];
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( is_string( $cf_value ) ) {
				$value = $cf_value;
			} elseif ( is_array( $cf_value ) && isset( $cf_value[ $show ] ) ) {
				if ( $show === 'total' ) {
					$value = $cf_value['count'] > 0 ? (float)( $cf_value['total'] / $cf_value['count'] ) : 0;
				} else {
					$value = $cf_value[ $show ];
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'rating' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'total' => __( 'Vote Result', 'tbp' ),
					'count' => __( 'Vote Counts', 'tbp' ),
				),
			),
		);
	}
}