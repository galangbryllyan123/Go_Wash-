<?php
/**
 * Maps PTB's "video" field type to "video" & "text" field in Builder
 *
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBLinkButton extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url', 'video', 'audio' );
	}

	public static function get_label():string {
		return __( 'PTB (Link Button)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['field'] ) ) {
			$show = isset( $args['show'] ) && $args['show'] === 'title' ? 0 : 1;
			$field_name = explode( ':', $args['field'] )[1];
			$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
			if ( isset( $cf_value[ $show ] ) ) {
			    $value = $cf_value[ $show ];
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'link_button' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'title' => __( 'Link Title', 'tbp' ),
					'url' => __( 'Link URL', 'tbp' ),
				),
			),
		);
	}
}