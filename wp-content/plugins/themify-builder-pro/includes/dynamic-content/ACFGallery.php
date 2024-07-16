<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFGallery extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_acf',false );
	}

	public static function get_category():string {
		return 'acf';
	}

	public static function get_type():array {
		return array( 'wp_editor', 'gallery' );
	}

	public static function get_label():string {
		return __( 'ACF (Gallery)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$cf_value = Themify_Builder_Plugin_Compat_acf::get_field_value( $args );
			if ( ! empty( $cf_value ) ) {
				$ids = wp_list_pluck( $cf_value, 'ID' );
				$value = '[gallery ids="' .  implode( ',', $ids ) . '"]';
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
				'dataset_args' => [ 'type' => 'gallery' ],
			),
		);
	}
}
