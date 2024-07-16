<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_PostTerms extends Tbp_Dynamic_Item {

	public static function get_category():string {
		return 'post';
	}

	public static function get_label():string {
		return __( 'Post (Taxonomy) Terms', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value = '';
		if ( ! empty( $args['tax'] ) ) {
			$separator = isset( $args['sep'] ) ? $args['sep'] : ',';
			$terms = get_the_terms( get_the_ID(), $args['tax'] );
			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$value = wp_list_pluck( $terms, 'name' );
				$value = implode( $separator, $value );
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
			),
			array(
				'label' => 'sep',
				'id' => 'sep',
				'type' => 'text'
			)
		);
	}
}
