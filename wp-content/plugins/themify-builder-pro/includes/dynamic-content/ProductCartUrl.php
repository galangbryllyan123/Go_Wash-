<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductCartUrl extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return themify_is_woocommerce_active();
	}

	public static function get_category():string {
		return 'wc';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor', 'url' );
	}

	public static function get_label():string {
		return __( 'Product Add To Cart URL', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$product=wc_get_product(!empty( $args['post_id'] )?$args['post_id']:get_the_ID());
		return !empty($product)?$product->add_to_cart_url():'';
	}
}
