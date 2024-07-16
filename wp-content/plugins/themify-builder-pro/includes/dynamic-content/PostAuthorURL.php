<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorURL extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	public static function get_label():string {
		return __( 'Post Author URL', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$display = $args['display']  ?? 'url';
		$user_id = get_post_field( 'post_author', get_the_ID() );
		return $display === 'url'?get_the_author_meta( 'url', $user_id ):get_author_posts_url( $user_id );
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'disp',
				'id' => 'display',
				'type' => 'select',
				'options' => array(
					'url' => __( 'Website', 'tbp' ),
					'archive' => __( 'Author Archive Page', 'tbp' ),
				),
			)
		);
	}
}
