<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductAttributes extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return themify_is_woocommerce_active();
	}

	public static function get_category():string {
		return 'wc';
	}

	public static function get_type():array {
		return array( 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'Product Attributes', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$product = wc_get_product(!empty( $args['post_id'] )?$args['post_id']:get_the_ID());
		ob_start();
		if ( ! empty( $product ) ) {
			wc_display_product_attributes( $product );
		}
		return ob_get_clean();
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_prdid',
				'id' => 'post_id',
				'type' => 'number',
				'help' => 'tbp_prdidh'
			),
		);
	}
}
