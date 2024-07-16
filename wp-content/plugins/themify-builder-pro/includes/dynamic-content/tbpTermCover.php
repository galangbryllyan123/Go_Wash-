<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_tbpTermCover extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'general';
	}

	public static function get_type():array {
		return array( 'image', 'url' );
	}

	public static function get_label():string {
		return __( 'Term Cover Image', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$cat = get_queried_object();
			$value = get_term_meta( $cat->term_id, 'tbp_cover', true );
		}

		return $value;
	}

	public static function get_options():array {
		return array();
	}
}
