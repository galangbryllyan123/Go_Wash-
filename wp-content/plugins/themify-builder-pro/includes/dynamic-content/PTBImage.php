<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBImage extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'image', 'url' );
	}

	public static function get_label():string {
		return __( 'PTB (Image)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value='';
		if(!empty($args['field'])){
			$field_name = explode( ':', $args['field'] );
			$value = get_post_meta(get_the_ID(), "ptb_{$field_name[1]}", true );
			$value = is_array( $value ) && isset($value[1])?$value[1]:'';
		}
		return $value;
		
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'image' ),
			),
		);
	}
}