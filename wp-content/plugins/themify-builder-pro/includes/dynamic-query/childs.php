<?php

class Tbp_Dynamic_Query_Childs {

	static function get_id():string {
		return 'childs';
	}

	static function get_label():string {
		return __( 'Children of current post', 'tbp' );
	}

	static function get_options():array {
		return array();
	}

	static function pre_get_posts( &$query ):bool {
		$post_id = get_the_ID();
		$query->set( 'post_type', get_post_type( $post_id ) );
		$query->set( 'post_parent', $post_id );

		return true;
	}
}