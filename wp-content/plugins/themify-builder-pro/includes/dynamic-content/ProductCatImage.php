<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductCatImage extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return themify_is_woocommerce_active();
	}

	public static function get_category():string {
		return 'wc';
	}

	public static function get_type():array {
		return array( 'image', 'url' );
	}

	public static function get_label():string {
		return __( 'Product Category Image', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		global $product;

		$value = '';
		if ( is_product_category() ) {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); 
			$value = wp_get_attachment_url( $thumbnail_id );
		} elseif ( ! empty( $product ) ) {
			$terms = get_the_terms( $product->get_ID(), 'product_cat' );
			if ( ! is_wp_error( $terms ) && ! empty( $terms[0] ) ) {
				$thumbnail_id = get_term_meta( $terms[0]->term_id, 'thumbnail_id', true ); 
				$value = wp_get_attachment_url( $thumbnail_id );
			}
		}

		return $value;
	}

	public static function get_options():array {
		return array(
		);
	}
}
