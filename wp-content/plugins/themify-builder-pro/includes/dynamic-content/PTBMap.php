<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBMap extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'address' );
	}

	public static function get_label():string {
		return __( 'PTB (Map)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value='';
		if(!empty($args['field'])){
			$field_name = explode( ':', $args['field'] )[1];
			$value = get_post_meta(get_the_ID(), "ptb_{$field_name}", true );
			if ( is_array( $value ) ) {
			    if ( isset( $args['display']) &&  $args['display']=== 'info' ) {
					$value = $value['info'];
				} elseif(!empty($value['place'])){
					$value = json_decode( $value['place'], true );
					$value = !empty($value)?($value['location']['lat'] . ', ' . $value['location']['lng']):'';
				}else{
					$value='';
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
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'map' ),
			),
			array(
				'label' => 'disp',
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'latlng' => 'ltlng',
					'info' => 'infowin'
				),
				'help'=>'infowinh'
			),
		);
	}
}