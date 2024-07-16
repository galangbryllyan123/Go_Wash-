<?php
/**
 * Maps PTB's "video" field type to "wp_editor" field in Builder
 *
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBVideoPlaylist extends Tbp_Dynamic_Item {

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
		return __( 'PTB (Video Playlist)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		if ( empty( $args['field'] ) || ! function_exists( 'ptb_get_field' ) ) {
			return '';
		}

		$field_name = explode( ':', $args['field'] );
        return ptb_get_field( $field_name[1], [
			'columns' => $args['columns']??1
		] );
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
				'label' => 'clmns',
				'id' => 'columns',
				'type' => 'select',
				'options' => array(
					1 => 1,
					2 => 2,
					3 => 3,
					4 => 4,
				),
			),
		);
	}
}