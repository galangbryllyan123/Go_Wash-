<?php

class Builder_Timeline_Post_Source {

	public function get_id() {
		return 'posts';
	}

	public function get_name() {
		return __( 'Posts', 'builder-timeline' );
	}
        
	/**
	 * Gets the data from a "query_category" field and
	 * returns a formatted "tax_query" array expected by WP_Query.
	 *
	 * @return array
	 */
	private static function parse_query_category_field( $value, $taxonomy = 'category' ) {
		$query = array();
		if ( '0' !== $value ) {
			$terms =  explode( ',', $value );
			$ids_in = $ids_in=$slugs_in=$slugs_not_in=array();
			foreach($terms as $a){
			    $a = trim($a);
			    if(is_numeric( $a )){
				if($a[0]==='-'){
				   $ids_not_in[] = abs($a); 
				}
				else{
				    $ids_in[]=$a;
				}
			    }
			    else if($a!==''){
				if($a[0]==='-'){
				   $slugs_not_in[] = abs($a); 
				}
				else{
				    $slugs_in[]=$a;
				}
			    }
			}
			unset($terms);

			if ( ! empty( $ids_in ) ) {
				$query[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => $ids_in
				);
			}
			if ( ! empty( $ids_not_in ) ) {
				$query[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'id',
					'terms' => $ids_not_in,
					'operator' => 'NOT IN'
				);
			}
			if ( ! empty( $slugs_in ) ) {
				$query[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $slugs_in
				);
			}
			if ( ! empty( $slugs_not_in ) ) {
				$query[] = array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $slugs_not_in,
					'operator' => 'NOT IN'
				);
			}
		}
		return $query;
	}

	public function get_items(array &$args ):array {
		global  $paged, $post;
		$items = array();
		$args+= array(
			'post_type_timeline' => 'post',
			'tax_timeline' => 'category',
			'category_post_timeline' => '',
			'post_per_page_post_timeline' => 4,
			'offset_post_timeline' => 0,
			'order_post_timeline' => 'desc',
			'orderby_post_timeline' => 'date',
			'display_post_timeline' => 'excerpt',
			'hide_feat_img_post_timeline' => 'no',
			'image_size_post_timeline' => '',
			'img_width_post_timeline' => '',
			'img_height_post_timeline' => ''
		);
		if( $args['category_post_timeline'] !== '' ){
			$args['category_post_timeline'] = method_exists( 'Themify_Builder_Component_Module', 'get_param_value' )
				? Themify_Builder_Component_Module::get_param_value( $args['category_post_timeline'] )
				: Themify_Builder_Component_Base::get_param_value( $args['category_post_timeline'] );
		}
		$paged = method_exists( 'Themify_Builder_Component_Module', 'get_paged_query' )
			? Themify_Builder_Component_Module::get_paged_query()
			: Themify_Builder_Component_Base::get_paged_query();
		$query = array(
			'post_type' => $args['post_type_timeline'],
			'posts_per_page' => $args['post_per_page_post_timeline'],
			'order' => $args['order_post_timeline'],
			'orderby' => $args['orderby_post_timeline'],
			'paged' => $paged,
			'suppress_filters' => false,
			'ignore_sticky_posts' => true,
		);
		if ( $args['offset_post_timeline']  !== '' ) {
			$query['offset'] = ( ( $paged - 1 ) * (int) $args['post_per_page_post_timeline']  ) + (int) $args['offset_post_timeline'];
		}
		$query['tax_query'] = self::parse_query_category_field( $args['category_post_timeline'], $args['tax_timeline'] );

		if ( method_exists( 'Themify_Builder_Model', 'parse_query_filter' ) ) {
			Themify_Builder_Model::parse_query_filter( $args, $query );
		}

		$query = new WP_Query( apply_filters( 'builder_timeline_source_post_query', $query, $args ) );
		$date_format = get_option( 'date_format' );
		$show_img='yes' !== $args['hide_feat_img_post_timeline'];

		if ( $query->have_posts() ) {
			while( $query->have_posts() ) {
				$query->the_post();
				$has_img = $show_img ? has_post_thumbnail() : false;
				$item = array(
					'id' => get_the_ID(),
					'title' => get_the_title(),
					'link' => get_permalink(),
					'date' => mysql2date( 'Y-m-d G:i:s', $post->post_date ), /* do not use get_the_date to avoid translation of the date which will break strtotime */
					'date_formatted' => date_i18n( $date_format, strtotime( $post->post_date ) ),
					'hide_featured_image' =>!$has_img,
					'image' => $has_img ? themify_get_image( 'urlonly=true&w='.$args['img_width_post_timeline'] .'&h=' . $args['img_height_post_timeline'] ) : null,
					'hide_content' => 'none' === $args['display_post_timeline'],
					'content' => 'content' === $args['display_post_timeline'] ? get_the_content() : get_the_excerpt(),
				);
				$items[] = $item;
			}
		}
		wp_reset_postdata();

		return apply_filters( 'builder_timeline_source_post_items', $items );
	}
}