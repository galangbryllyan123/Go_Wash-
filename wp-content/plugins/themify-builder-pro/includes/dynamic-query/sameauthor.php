<?php


class Tbp_Dynamic_Query_Sameauthor {

	static function get_id():string {
		return 'sameauthor';
	}

	static function get_label():string {
		return __( 'Posts by the current author', 'tbp' );
	}

	static function get_options():array {
		return array(
            array(
				'id' => 'sameauthor_match_post_type',
				'type' => 'select',
				'label' => __( 'Match Current Post Type', 'tbp' ),
				'choose' => true,
				'binding' => [
					'yes' => [ 'hide' => 'tbpdq_sameauthor_post_types' ],
					'no' => [ 'show' => 'tbpdq_sameauthor_post_types' ],
				],
            ),
            array(
				'id' => 'sameauthor_post_types',
				'type' => 'select',
				'label' => __( 'Post Types', 'tbp' ),
				'dataset' => 'post_type',
				'multiple' => true,
            ),
		);
	}

	static function pre_get_posts( &$query, ?array $settings=array() ):bool {
		global $post;
		if ( empty( $post ) ) {
			return false;
		}

		if ( isset( $settings['sameauthor_match_post_type'] ) && $settings['sameauthor_match_post_type'] === 'no' ) {
			$query->set( 'post_type', $settings['sameauthor_post_types'] ?? 'any' );
		} else {
			$query->set( 'post_type', get_post_type( $post ) );
		}

		$query->set( 'author', $post->post_author );

		return true;
	}
}