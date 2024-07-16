<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostTermsClass extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Post (Taxonomy) Terms', 'tbp' );
	}

	public static function get_type():array {
		return array( 'custom_css' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['tax'] ) ) {
			$terms = get_the_terms( get_the_ID(), $args['tax'] );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    $value .= ' cat-' . $term->term_id . ' cat-' . $term->slug;
                }
			}
		}

		return $value;
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'query_tax_id',
				'id' => 'tax',
				'type' => 'select',
				'dataset'=>'taxonomy'
			)
		);
	}
}
