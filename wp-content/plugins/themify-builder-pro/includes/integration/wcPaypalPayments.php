<?php
/**
 * Builder Plugin Compatibility Code
 *
 * @package    Themify_Builder Pro
 */
/**
 * @link https://wordpress.org/plugins/woocommerce-paypal-payments/
 */
class Themify_Builder_Plugin_Compat_wcPaypalPayments {

	static function init() {
		add_action( 'woocommerce_paypal_payments_single_product_renderer_hook', [ __CLASS__, 'woocommerce_paypal_payments_single_product_renderer_hook' ] );
	}

	/**
	 * hook the PayPal button to Add To Cart module
	 */
	static function woocommerce_paypal_payments_single_product_renderer_hook(?string $name ):?string {
		global $product;
		$p = is_object($product)? $product : wc_get_product( get_the_ID() );
		return empty( $p )?$name:'woocommerce_' . $p->get_type() . '_add_to_cart';
	}
}