<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFImage extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_acf',false );
	}

	public static function get_category():string {
		return 'acf';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'image', 'url' );
	}

	public static function get_label():string {
		return __( 'ACF (Image)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$cf_value = Themify_Builder_Plugin_Compat_acf::get_field_value( $args );
			if ( is_string( $cf_value ) ) {
				$value = $cf_value;
			} elseif ( ! empty( $cf_value['url'] ) ) {
				$value = $cf_value['url'];
			}
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'key',
				'type' => 'select',
				'dataset' => 'acf_fields',
				'dataset_args' => [ 'type' => 'image' ],
			),
		);
	}
}
