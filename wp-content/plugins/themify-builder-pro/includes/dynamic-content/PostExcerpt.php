<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostExcerpt extends Tbp_Dynamic_Item {

	private static $length = null;

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'textarea', 'wp_editor', 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'Post Excerpt', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		if ( ! empty( $args['length'] ) ) {
		    self::$length =(int)$args['length'];
		    add_filter( 'excerpt_length', array( __CLASS__, 'excerpt_length' ), 1000 );
		}
		$value = get_the_excerpt( !empty($args['post_id'])?$args['post_id']:null );
		if ( ! empty( $args['length'] ) ) {
		    remove_filter( 'excerpt_length', array( __CLASS__, 'excerpt_length' ), 1000 );
		}
		self::$length = null;
		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'exclen',
				'id' => 'length',
				'type' => 'number',
				'help' => 'exch',
			),
			array(
				'label' => 'pstid',
				'id' => 'post_id',
				'type' => 'number',
				'help' => 'tbp_pstidh'
			),
		);
	}

	public static function excerpt_length(?int $length ):?int {
		return self::$length;
	}
}