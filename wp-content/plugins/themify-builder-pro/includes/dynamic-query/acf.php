<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://www.advancedcustomfields.com/
 */

class Tbp_Dynamic_Query_Acf {

	static function get_id():string {
		return 'acf';
	}

	static function get_label():string {
		return __( 'ACF Pro Relational', 'tbp' );
	}

	static function get_options():array {
		return array(
			array(
				'id' => 'acf_field',
				'type' => 'select',
				'label' => __( 'ACF Relational Field', 'tbp' ),
				'options' => Themify_Builder_Plugin_Compat_acf::get_fields_by_type( [ 'relationship', 'post_object', 'taxonomy' ] ),
			),
			array(
				'id' => 'acf_ctx',
				'type' => 'select',
				'label' => __( 'ACF Context', 'tbp' ),
				'options' => array(
					'' => __( 'Current post', 'tbp' ),
					'term' => __( 'Taxonomy terms', 'tbp' ),
					'user' => __( 'Current logged-in user', 'tbp' ),
					'author' => __( 'Author of current post', 'tbp' ),
					'option' => __( 'Option', 'tbp' ),
				),
			),
		);
	}

	static function pre_get_posts( &$query, ?array $settings = [] ):bool {
		if (!empty( $settings['acf_field'] ) ) {
			$field_id = explode( ':', $settings['acf_field'] )[1];
			$value = get_field( $field_id, Themify_Builder_Plugin_Compat_acf::get_context( $settings ) );
			$field_object = get_field_object( $field_id );
			if ( ! empty( $value ) && ! empty( $field_object ) ) {
				/* Taxonomy field type in ACF */
				if ( $field_object['type'] === 'taxonomy' ) {
					if ( $field_object['return_format'] === 'object' ) {
						$value = wp_list_pluck( $value, 'term_id' );
					}
					/* get the ID of the posts assigned to the selected taxonomy term */
					$value = get_posts( [
						'fields' => 'ids',
						'post_type' => 'any',
						'posts_per_page' => $query->get( 'posts_per_page' ),
						'tax_query' => [
							[
								'taxonomy' => $field_object['taxonomy'],
								'field' => 'term_id',
								'terms' => $value,
							]
						]
					] );
				} else { /* Relationship and Post Object field types in ACF */
					if ( $field_object['type'] === 'post_object' && $field_object['multiple'] !== 1 ) {
						$value = [ $value ];
					}
					if ( $field_object['return_format'] === 'object' ) {
						$value = wp_list_pluck( $value, 'ID' );
					}
					$query->set( 'posts_per_page', -1 );
				}

				/* disable any post from showing in the module */
				if (!empty( $value ) ) {
					$query->set( 'post_type', 'any' );
					$query->set( 'post__in', $value );
					return true;
				}
			}
		}
		return false;
	}
}