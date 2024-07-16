<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_MediaLibrary extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'image', 'url' );
	}

	public static function get_label():string {
		return __( 'Item from Media Library', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value='';
		if(isset($args['attachment_id'])){
		    $value = wp_get_attachment_url( $args['attachment_id'] );
		    if(!$value){
				$value='';
		    }
		}
		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'id',
				'id' => 'attachment_id',
				'type' => 'number'
			),
		);
	}
}
