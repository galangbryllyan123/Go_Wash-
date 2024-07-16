<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PTBRelations extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return class_exists( 'Themify_Builder_Plugin_Compat_ptb',false );
	}

	public static function get_category():string {
		return 'ptb';
	}

	public static function get_type():array {
		return array( 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'PTB (Relations)', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$args = wp_parse_args( $args, array(
			'show' => 'grid',
			'columns' => 1,
			'minSlides' => 1,
			'autoHover' => 1,
			'pause' => 1,
			'hide_pager' => 0,
			'hide_controls' => 0,
			'orderby' => 'post__in',
			'order' => 'asc',
			'limit' => '',
			'masonry' => 0,
		) );

		if ( empty( $args['field'] ) ) {
			return '';
		}

		list( $post_type, $field_name ) = explode( ':', $args['field'] );
		$ptb = PTB::get_option()->get_options();

		if ( ! isset( $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ] ) ) {
			return '';
		}

		$ptb_options = PTB::get_option();
		$def = $ptb['cpt'][ $post_type ]['meta_boxes'][ $field_name ];
		$rel_options = PTB_Relation::get_option();
		$template = $rel_options->get_relation_template( $def['post_type'], get_post_type() );
		if ( ! $template ) {
			return '';
		}
		$themplate_layout = $ptb_options->get_post_type_template( $template['id'] );
		if ( ! isset( $themplate_layout['relation']['layout'] ) ) {
			return '';
		}

		$content = '';
		$is_shortcode = PTB_Public::$shortcode;
		PTB_Public::$shortcode = true;
		$ver = PTB::get_plugin_version( WP_PLUGIN_DIR . '/themify-ptb-relation/themify-ptb-relation.php' );
		$themplate = new PTB_Form_PTT_Them( 'ptb', $ver );
		$cf_value = get_post_meta( get_the_ID(), "ptb_{$field_name}", true );
		$relType = ! empty( $cf_value['relType'] ) ? (int) $cf_value['relType'] : 1;
		$ids = ! empty( $cf_value['ids'] ) ? $cf_value['ids'] : $cf_value;
		$ids = array_filter( is_array($ids)?$ids:explode( ', ', $ids ) );
		if ( empty( $ids ) ) {
			return '';
		}
		$query_args = array(
			'post_type' => $def['post_type'],
			'post_status' => 'publish',
			'order' => $args['order'],
			'orderby' => $args['orderby'],
			'no_found_rows' => 1,
		);
		if ( $relType === 1 ) {
			$query_args['post__in'] = $ids;
			$query_args['posts_per_page'] = empty( $args['limit'] ) ? count( $ids ) : $args['limit'];
		} else {
			$tmp = array();
			$terms = get_terms( array(
				'include' => $ids
			) );
			foreach ( $terms as $term ) {
				$tmp[ $term->taxonomy ][] = $term->term_id;
			}
			$value = array();
			$temp = array();
			foreach ( $tmp as $k => $v ) {
				$value[] = array(
					'taxonomy' => $k,
					'field' => 'term_id',
					'terms' => $v
				);
			}
			if ( ! empty( $value ) ) {
				$value['relation'] = 'AND';
				$query_args['tax_query'] = $value;
			}
			if ( empty( $args['limit'] ) ) {
				$query_args['nopaging'] = 1;
			} else {
				$query_args['posts_per_page'] = $args['limit'];
			}
		}
		global $post;
		if(isset($post)){
			$saved_post = clone $post;
		}
		$query = new WP_Query($query_args);
		$item_tag = 'div';
		while ( $query->have_posts() ) {
			$query->the_post();
			$cmb_options = $post_support = $post_meta = $post_taxonomies = array();
			$ptb_options->get_post_type_data( $def['post_type'], $cmb_options, $post_support, $post_taxonomies );
			$post_meta['post_url'] = get_permalink();
			$post_meta['taxonomies'] = ! empty( $post_taxonomies ) ? wp_get_post_terms( get_the_ID(), array_values($post_taxonomies ) ) : array();
			$post_meta = array_merge( $post_meta, get_post_custom(), get_post( '', ARRAY_A ) );
			$item_class = $args['show'] === 'slider' ? 'tf_swiper-slide' : 'ptb_relation_item';
			$content .= '<' . $item_tag . ' class="' . $item_class . '">' . $themplate->display_public_themplate( $themplate_layout['relation'], $post_support, $cmb_options, $post_meta, $def['post_type'], false ) . '</' . $item_tag . '>';
		}
		PTB_Public::$shortcode = $is_shortcode;
		$query=null;
		/* reset query back to original */
		if ( isset( $saved_post )) {
			$post = $saved_post;
			setup_postdata( $saved_post );
			unset($saved_post);
		}
		$wrap_class = 'ptb_loops_shortcode ptb_relation_posts tf_clearfix';

		if ( $args['show'] === 'slider' ) {
			if ( ! wp_script_is( 'ptb-relation' ) ) {
				wp_enqueue_script( 'ptb-relation' );
			}
			$content =
			'<div
				class="ptb_slider tf_swiper-container"
				data-visible="' . $args['minSlides'] .'"
				data-pause_hover="' . $args['autoHover'] . '"
				data-auto="' . $args['pause'] .'"
				data-pager="' . ( (int) $args['hide_pager'] === 1 ? 0 : 1 ) . '"
				data-slider_nav="' . ( (int) $args['hide_controls'] === 1 ? 0 : 1 ) . '"
				data-speed="1000"
			>
				<div class="tf_swiper-wrapper">' .
					$content .
				'</div>' .
			'</div>';
		} else {
			$wrap_class .= ' ptb_loops_wrapper ptb_grid';
			$wrap_class .= ' ptb_grid' . $args['columns'];
			if ( $args['masonry'] ) {
				wp_enqueue_script( 'ptb-relation' );
				$wrap_class .= ' ptb_relation_masonry';
			}
		}

		if ( ! empty( $content ) ) {
			$content = 
				'<div class="ptb_module ptb_relation">'
					. '<div class="' . $wrap_class . '">'
						. $content
					. '</div>'
				. '</div>';
		}

		return $content;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_f',
				'id' => 'field',
				'type' => 'select',
				'options' => Themify_Builder_Plugin_Compat_ptb::get_fields_by_type( 'relation' ),
			),
			array(
				'label' => 's',
				'id' => 'show',
				'type' => 'select',
				'options' => array(
					'grid' => 'grid',
					'slider' => 'slider',
				),
				'binding' => array(
					'grid' => array( 'show' => array( 'columns', 'masonry' ), 'hide' => array( 'minSlides', 'autoHover', 'pause', 'hide_pager', 'hide_controls' ) ),
					'slider' => array( 'hide' => array( 'columns', 'masonry' ), 'show' => array( 'minSlides', 'autoHover', 'pause', 'hide_pager', 'hide_controls' ) ),
				),
			),
			array(
				'label' => __( 'Grid Columns', 'tbp' ),
				'id' => 'columns',
				'type' => 'select',
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6 ),
			),
			array(
				'label' => 'masnry',
				'id' => 'masonry',
				'type' => 'select',
				'options' => array( 'no', 'y' ),
			),
			array(
				'label' => 'visibsl',
				'id' => 'minSlides',
				'type' => 'select',
				'options' => array( 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7 ),
			),
			array(
				'label' => 'pauseonh',
				'id' => 'autoHover',
				'type' => 'select',
				'options' => array(  1=> 'y', 0 => 'no' ),
			),
			array(
				'label' => 'ascroll',
				'id' => 'pause',
				'type' => 'select',
				'options' => array( 1 => '1s', 2 => '2s', 3 => '3s', 4 => '4s', 5 => '5s', 6 => '6s', 7 => '7s', 8 => '8s', 9 => '9s', 0 => 'off' ),
			),
			array(
				'label' => __( 'Hide Slider Pagination', 'tbp' ),
				'id' => 'hide_pager',
				'type' => 'select',
				'options' => array( 1 => 'y', 0=> 'no'),
			),
			array(
				'label' => __( 'Hide Slider arrow buttons', 'tbp' ),
				'id' => 'hide_controls',
				'type' => 'select',
				'options' => array(  'no', 'y' ),
			),
			array(
				'label' => 'npost',
				'id' => 'limit',
				'type' => 'text',
				'help' => 'nposth'
			),
			array(
				'label' => 'order',
				'id' => 'order',
				'type' => 'select',
				'options' => array(
					'asc' => 'asc',
					'desc' => 'descend'
				),
			),
			array(
				'label' => 'orderby',
				'id' => 'orderby',
				'type' => 'select',
				'options' => array(
					'date' => 'date',
					'id' => 'id',
					'author' => 'author',
					'title' => 'title',
					'name' => 'name',
					'modified' => 'md',
					'rand' => 'rand',
					'comment_count' => 'com_count',
					'menu_order' => __( 'Menu Order', 'tbp' ),
				),
			),
		);
	}
}