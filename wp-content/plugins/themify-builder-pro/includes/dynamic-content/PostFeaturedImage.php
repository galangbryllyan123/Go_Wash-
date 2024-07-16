<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostFeaturedImage extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'image', 'url' );
	}

	public static function get_label():string {
		return __( 'Post Featured Image', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = get_the_post_thumbnail_url( $args['post_id']??get_the_ID(), $args['size'] ?? 'thumbnail' );
		return $value===false?'':$value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'size',
				'id' => 'size',
				'type' => 'select',
				'image_size' => true
			),
			array(
				'label' => 'pstid',
				'id' => 'post_id',
				'type' => 'number',
				'help' => 'tbp_pstidh'
			),
		);
	}
}
