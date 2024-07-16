<?php
/**
 * Maps PTB's "video" field type to "video" & "text" field in Builder
 *
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBVideo extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'url', 'video' );
	}

	public static function get_label():string {
		return __( 'PTB (Video)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) ? $args['show'] : 'url';
			if ( PTB_Repeater_Field::$in_the_loop ) {
				$n = PTB_Repeater_Field::$repeater_index;
			} else {
				$n = ! empty( $args['n'] ) ? ( (int) $args['n'] - 1 ) : 0;
			}
			$field_name = explode( ':', $args['field'] )[1];
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( isset( $cf_value[ $show ][ $n ] ) && is_array( $cf_value[ $show ] ) ) {
			    $value = $cf_value[ $show ][ $n ];
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'video' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'url' => __( 'Video File URL', 'tbp' ),
					'title' => 'title',
					'description' => 'desc'
				),
			),
			array(
				'label' => __( '# Item', 'tbp' ),
				'id' => 'n',
				'type' => 'text',
				'help' => __( 'Number of item in repeatable fields to show.', 'tbp' ),
			),
		);
	}
}