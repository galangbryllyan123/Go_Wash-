<?php


class Tbp_Dynamic_Query_On {

	static function get_id():string {
		return 'on';
	}

	static function get_label():string {
		return __( 'Main Query', 'tbp' );
	}

	static function get_options():array {
		return array();
	}

	static function pre_get_posts( &$query ):bool {
        if ( ! ( is_archive() || is_home() || is_search() ) ) {
			return false;
		}

		global $wp_query;

		$query->query_vars = $wp_query->query_vars;
		if ( isset( $query->query['order'] ) ) {
			$query->query_vars['order'] = $query->query['order'];
		}
		if ( isset( $query->query['orderby'] ) ) {
			$query->query_vars['orderby'] = $query->query['orderby'];
		}
		if ( isset( $query->query['posts_per_page'] ) ) {
			$query->query_vars['posts_per_page'] = $query->query['posts_per_page'];
		}
		if ( isset( $query->query['offset'] ) ) {
			$query->query_vars['offset'] = $query->query['offset'];
		}
		if ( isset( $query->query['paged'] ) ) {
			$query->query_vars['paged'] = $query->query['paged'];
		}
		if ( $wp_query->is_home() ) {
			$query->query_vars['ignore_sticky_posts'] = false;
		}
        if ( $wp_query->is_search() && 'post'!==$query->query['post_type'] ) {
            $query->query_vars['post_type'] = $query->query['post_type'];
        }

		/* WC quirk: on its archive pages the "post_type" is empty. */
		if ( themify_is_woocommerce_active() && Tbp_Utils::is_wc_archive() ) {
			$query->query_vars['post_type'] = 'product';
		}

		return true;
	}
}
