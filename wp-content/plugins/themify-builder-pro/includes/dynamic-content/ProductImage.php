<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductImage extends Tbp_Dynamic_Item {

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
		return __( 'Product Image', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$product = wc_get_product(!empty( $args['post_id'] )?$args['post_id']:get_the_ID());
		return  !empty($product)?self::get_image( $product ):'';
	}

	/**
	 * Get the featured image for a product.
	 * For variations, the image for the base product is used as substitute.
	 *
	 * @return string
	 */
	private static function get_image( $product ) {
		$imageId = $product->get_image_id();
		if ( empty($imageId) ) {
			$p = $product->get_parent_id();
			if ( !empty($p) ) {
				$parent_product = wc_get_product( $p );
				if ( !empty($parent_product) ) {
					$imageId = $parent_product->get_image_id();
				}
			}
		}

		return $imageId ? wp_get_attachment_url( $imageId ) : '';
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
