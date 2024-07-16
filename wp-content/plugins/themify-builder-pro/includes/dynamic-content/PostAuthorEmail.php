<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostAuthorEmail extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	public static function get_label():string {
		return __( 'Post Author Email', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		global $post;
		$value = isset($post)?get_the_author_meta( 'email', $post->post_author):'';
		if ( ! empty( $value ) ) {
			$mailto = isset( $args['mailto'] ) && $args['mailto'] === 'y' ? 'mailto:' : '';
			$value = $mailto . $value;
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => __( 'Prepend "mailto:"', 'tbp' ),
				'id' => 'mailto',
				'type' => 'select',
				'rchoose' =>true
			)
		);
	}
}
