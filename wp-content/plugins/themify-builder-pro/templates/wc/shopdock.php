<?php
/**
 * Template for cart icon
 * @package Themify Builder Pro
 * @since 1.0.0
 */
?>
<div class="tbp_shopdock tf_overflow">
	<?php
	// check whether cart is not empty
	if ( !empty( WC()->cart->get_cart() )):
		if(themify_is_ajax())://we don't need to render on page loading,wc will do it by js even if we will render it wc will replace it by js
		?>
		<div class="tbp_cart_wrap tf_rel tf_opacity tf_hidden tf_textl tf_h tf_box">
		    <?php
		    if(current_user_can( 'manage_woocommerce' ) && 'yes' !== get_option( 'woocommerce_enable_ajax_add_to_cart' )){
			echo sprintf('<div class="tbp_admin_msg">%s <a href="%s">%s</a>.</div>',
			    __('WooCommerce Ajax add to cart option needs to be enabled to use this Ajax cart.','tbp'),
			    admin_url('admin.php?page=wc-settings&tab=products'),
			    __('Enable it on WooCommerce settings','tbp')
			);
		    }
		    ?>
		    <div class="tbp_cart_list tf_h tf_box tf_scrollbar">
			<?php Themify_Builder_Component_Base::retrieve_template('wc/loop/loop-product-cart.php',null,TBP_DIR . 'templates');?>
		    </div>
		    <!-- /cart-list -->
		    <div class="tbp_cart_checkout_wrap tf_w tf_box">
			<div class="tbp_cart_total tf_left">
			    <span class="tbp_cart_amount"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
			    <a class="tbp_view_cart" href="<?php echo esc_url( wc_get_cart_url() ) ?>">
				<?php _e( 'view cart', 'tbp' ) ?>
			    </a>
			</div>
			<div class="tbp_checkout_button tf_right">
			    <button type="submit" onClick="document.location.href = '<?php echo wc_get_checkout_url(); ?>'; return false;"><?php _e( 'Checkout', 'tbp') ?></button>
			</div>
			<!-- /checkout-botton -->
		    </div>
		</div>
		<!-- /#cart-wrap -->
		<?php endif; // cart whether is not empty?>
	<?php else: ?>
		<?php
		if(function_exists('themify_get_shop_permalink')){
		    $shop_permalink= themify_get_shop_permalink();
		}
		elseif(function_exists('themify_shop_pageId')){
		   $shop_permalink=get_permalink(themify_shop_pageId());
		}
		else{
		    $shop_permalink = get_permalink(wc_get_page_id('shop'));
		}
		?>
		<span class="tbp_empty_shopdock tf_opacity tf_textl tf_box">
			<?php printf( __( 'Your cart is empty. Go to <a href="%s">Shop</a>', 'tbp' ), $shop_permalink ); ?>
		</span>
	<?php endif; // cart whether is not empty?>
</div>
