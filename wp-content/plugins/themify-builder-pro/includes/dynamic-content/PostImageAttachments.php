<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostImageAttachments extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_type():array {
		return array( 'gallery' );
	}

	public static function get_label():string {
		return __( 'Post Image Attachments', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		if ( $post = get_post( $args['post_id']??get_the_ID() ) ) {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'posts_per_page' => -1,
				'no_found_rows'=>true,
				'ignore_sticky_posts'=>true,
				'post_parent' => $post->ID,
				'exclude' => get_post_thumbnail_id( $post->ID ),
                'fields' => 'ids'
			) );

			if ( $attachments ) {
				return '[gallery ids="' . implode( ',', $attachments ) . '"]';
			}
		}
		return '';
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'pstid',
				'id' => 'post_id',
				'type' => 'number',
				'help' => 'tbp_pstidh'
			),
		);
	}
}
