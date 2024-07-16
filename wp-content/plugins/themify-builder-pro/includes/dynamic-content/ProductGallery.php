<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductGallery extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return themify_is_woocommerce_active();
	}

	public static function get_category():string {
		return 'wc';
	}

	public static function get_type():array {
		return array( 'gallery', 'wp_editor' );
	}

	public static function get_label():string {
		return __( 'Product Gallery', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value='';
		$product = wc_get_product(!empty( $args['post_id'] )?$args['post_id']:get_the_ID());
		if ( ! empty( $product ) ) {
			$value = $product->get_gallery_image_ids();
			if ( isset( $args['include_variation'] ) && $args['include_variation'] === 'y' && $product->is_type( 'variable' ) ) {
				$variation_images = wp_list_pluck( $product->get_available_variations(), 'image_id' );
				if ( is_array( $variation_images ) ) {
					$value = array_merge( $value, $variation_images );
				}
			}
		}

		return ! empty( $value ) ? '[gallery ids="' . implode( ',', $value ) . '"]' : '';
	}

	public static function get_options():array {
		return array(
			array(
				'label' => 'tbp_prdid',
				'id' => 'post_id',
				'type' => 'number',
				'help' => 'tbp_prdidh'
			),
			array(
				'id' => 'include_variation',
				'label' => __( 'Include Variation Images', 'tbp' ),
				'type' => 'select',
				'options' => array(
					'' => 'no',
					'y' => 'y'
				)
			),
		);
	}
}
