<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ACFMap extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_acf',false );
	}

	public static function get_category():string {
		return 'acf';
	}

	public static function get_label():string {
		return __( 'ACF (Map)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['key'] ) ) {
			$display = isset( $args['display'] ) ? $args['display'] : 'address';
			$cf_value = Themify_Builder_Plugin_Compat_acf::get_field_value( $args );
			if ( ! empty( $cf_value ) ) {
				if ( $display === 'latlng' ) {
					if ( isset( $cf_value['lat'], $cf_value['lng'] ) ) {
						$value = $cf_value['lat'] . ',' . $cf_value['lng'];
					}
				} elseif ( isset( $cf_value[ $display ] ) ) {
					$value = $cf_value[ $display ];
				}
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
				'dataset_args' => [ 'type' => 'google_map' ],
			),
			array(
				'label' => 'disp',
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'address' => 'address',
					'lat' => __( 'Lat', 'tbp' ),
					'lng' => __( 'Lng', 'tbp' ),
					'latlng' => 'ltlng',
					'name' => 'name',
					'city' => __( 'City', 'tbp' ),
					'state' => __( 'State', 'tbp' ),
					'country' => __( 'Country', 'tbp' ),
				)
			),
		);
	}
}