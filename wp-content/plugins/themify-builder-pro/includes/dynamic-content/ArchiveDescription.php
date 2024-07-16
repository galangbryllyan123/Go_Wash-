<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ArchiveDescription extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Archive Description', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
        return get_the_archive_description();
	}
}
