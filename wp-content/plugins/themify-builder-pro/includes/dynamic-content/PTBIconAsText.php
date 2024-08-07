<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBIconAsText extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'url' );
	}

	public static function get_label():string {
		return __( 'PTB (Icon)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = $args['show']?? 'url';
			$field_name = explode( ':', $args['field'] )[1];
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( isset( $cf_value[ $show ][0] ) && is_array( $cf_value[ $show ] ) ) {
			    $value = $cf_value[ $show ][0];
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'icon' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'url' => __( 'Icon URL', 'tbp' ),
					'label' => 'ilabel'
				),
			),
		);
	}
}