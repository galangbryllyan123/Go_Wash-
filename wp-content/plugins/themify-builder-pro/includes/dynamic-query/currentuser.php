<?php

class Tbp_Dynamic_Query_Currentuser {

	static function get_id():string {
		return 'currentuser';
	}

	static function get_label():string {
		return __( 'Posts by the current logged-in user', 'tbp' );
	}

	static function get_options():array {
		return array(
            array(
				'id' => 'currentuser_post_type',
				'type' => 'select',
				'label' => __( 'Post Types', 'tbp' ),
				'dataset' => 'post_type',
				'multiple' => true,
            ),
		);
	}

	static function pre_get_posts( &$query, ?array $settings=array() ):bool {
		if ( ! is_user_logged_in() ) {
			return false;
		}

		$query->set( 'post_type', isset( $settings['currentuser_post_type'] ) ? $settings['currentuser_post_type'] : 'any' );
		$query->set( 'author', get_current_user_id() );

		return true;
	}
}