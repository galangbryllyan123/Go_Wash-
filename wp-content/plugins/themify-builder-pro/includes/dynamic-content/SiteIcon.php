<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_SiteIcon extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'image' );
	}

	public static function get_label():string {
		return __( 'Site Icon', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
	    $size = isset($args['size'])?(int)$args['size']:512;
	    $value = get_site_icon_url($size );
	    return $value?$value:'';
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'size',
				'id' => 'size',
				'class'=>'large',
				'type' => 'number',
				'help' => sprintf( __( 'Configured in <a href="%s" target="_blank">Customizer</a> > Site Identity tab.', 'tbp' ), admin_url( 'customize.php' ) ),
			),
		);
	}
}
