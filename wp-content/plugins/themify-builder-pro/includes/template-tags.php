<?php

if ( ! function_exists( 'tbp_get_terms_list' ) ) :
function tbp_get_terms_list( $post_id, $taxonomy, $args ) {
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return false;
	}

	$items = array();
	$args+=[
		'before' => '',
		'after' => '',
		'sep' => '',
		'cover' => false,
		'cover_w' => '',
		'cover_h' => '',
		'cover_class' => '',
		'link' => true,
	];

	foreach ( $terms as $term ) {
		$term_name = $term->name;
		if ( $args['cover'] === true ) {
			$cover_image = get_term_meta( $term->term_id, 'tbp_cover', true );
            if ( ! $cover_image ) {
                /* fallback: get WC's term Thumbnail */
                $cover_image = get_term_meta( $term->term_id, 'thumbnail_id', true );
            }
			if ( $cover_image ) {
				$cover_image = themify_get_image( [
					'src' => $cover_image,
					'w' => $args['cover_w'],
					'h' => $args['cover_h'],
					'class' => 'tbp_term_cover ' . $args['cover_class']
				] );
				$term_name = $cover_image . $term_name;
			}
		}
		if ( $args['link'] === true ) {
			$link = get_term_link( $term, $taxonomy );
			if ( ! is_wp_error( $link ) ) {
				$items[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term_name . '</a>';
			}
		} else {
			$items[] = $term_name;
		}
	}
    if ( ! empty( $args['sep'] ) ) {
        $args['sep'] = '<span class="tbp_term_sep">' . $args['sep'] . '</span>';
    }

	return $args['before'] . implode( $args['sep'], $items ) . $args['after'];
}
endif;