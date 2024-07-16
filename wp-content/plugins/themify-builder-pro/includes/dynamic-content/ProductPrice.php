<?php
/**
 * @package    Themify Builder Pro
 * @link       https://themify.me/
 */
class Tbp_Dynamic_Item_ProductPrice extends Tbp_Dynamic_Item {

	public static function is_available():bool {
		return themify_is_woocommerce_active();
	}

	public static function get_category():string {
		return 'wc';
	}

	public static function get_type():array {
		return array( 'text', 'textarea', 'wp_editor','slider_range' );
	}

	public static function get_label():string {
		return __( 'Product Price', 'tbp' );
	}

	public static function get_value(array $args = array()):?string {
		$value='';
		$product = wc_get_product(!empty( $args['post_id'] )?$args['post_id']:get_the_ID());
		if(!empty($product)){
			$value = isset( $args['format'] ) && 'p' === $args['format'] ? wp_strip_all_tags( wc_price( wc_get_price_to_display( $product ) ) ) : $product->get_price_html();
		}
		return $value;
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
                'id' => 'format',
                'label' => 'contfrmt',
                'type' => 'select',
                'options' => [
                    '' => 'tbp_html',
                    'p' => 'tbp_plain',
                ]
            )
		);
	}
}
