<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_SiteURL extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'url' );
	}

	public static function get_label():string {
		return __( 'Site URL', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		return home_url();
	}
}
