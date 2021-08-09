<?php
defined( 'ABSPATH' ) || exit;

/**
 * Compatibility with theme: Shoptimizer (by CommerceGurus).
 */
class FluidCheckout_ThemeCompat_Shoptimizer extends FluidCheckout {

	/**
	 * __construct function.
	 */
	public function __construct() {
		$this->hooks();
	}



	/**
	 * Initialize hooks.
	 */
	public function hooks() {
		// Late hooks
		add_action( 'init', array( $this, 'late_hooks' ), 100 );

	}



	/**
	 * Add or remove late hooks.
	 */
	public function late_hooks() {
		// Removes Coupon code from woocommerce after checkout form
		remove_action( 'woocommerce_after_checkout_form', 'woocommerce_checkout_coupon_form' );
		remove_action( 'woocommerce_before_cart', 'shoptimizer_cart_progress' );
		remove_action( 'woocommerce_before_checkout_form', 'shoptimizer_cart_progress', 5 );
	}
}

FluidCheckout_ThemeCompat_Shoptimizer::instance();
