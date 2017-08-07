<?php
/**
 * Product Add-ons ajax
 *
 * @package WC_Product_Addons/Classes/Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Product_Addon_Cart_Ajax class.
 */
class Product_Addon_Cart_Ajax {

	/**
	 * Handle ajax endpoints.
	 */
	public function __construct() {
		add_action( 'wp_ajax_wc_product_addons_calculate_tax', array( $this, 'calculate_tax' ) );
		add_action( 'wp_ajax_nopriv_wc_product_addons_calculate_tax', array( $this, 'calculate_tax' ) );
	}

	/**
	 * Calculate tax values for grand total (after options value)
	 * Used when we can't calculate tax from form values
	 * (since there 4 different combinations of how taxes can be displayed).
	 *
	 * @since 1.0.0
	 * @version 2.9.0
	 */
	public function calculate_tax() {

		// Make sure we have a total to calculate the tax on.
		$add_on_total = floatval( $_POST['add_on_total'] );
		if ( $add_on_total < 0 ) {
			wp_send_json( array(
				'result' => 'ERROR',
				'error'   => 'no-total',
			) );
		}

		$add_on_total_raw = floatval( $_POST['add_on_total_raw'] );

		// Make sure we have a valid producto so we can calculate tax.
		$product_id = intval( $_POST['product_id'] );
		$product    = wc_get_product( $product_id );
		if ( ! $product ) {
			wp_send_json( array(
				'result' => 'ERROR',
				'html'   => 'invalid-product',
			) );
		}

		$qty = ! empty( $_POST['qty'] ) ? absint( $_POST['qty'] ) : 1;

		// Return our price including tax and our price excluding tax.
		// When the tax is set to exclusive and display mode is set to inclusive, our price excluding tax is just the normal price.
		if ( 'no' === get_option( 'woocommerce_prices_include_tax' ) && 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) {
			wp_send_json( array(
				'result' => 'SUCCESS',
				'price_including_tax' => round( wc_get_price_including_tax( $product, array( 'qty' => $qty ) ) + $add_on_total, wc_get_price_decimals() ),
				'price_excluding_tax' => round( wc_get_price_excluding_tax( $product, array( 'qty' => $qty ) ) + $add_on_total_raw, wc_get_price_decimals() ),
			) );
		}

		wp_send_json( array(
			'result' => 'SUCCESS',
			'price_including_tax' => round( wc_get_price_including_tax( $product, array( 'qty' => $qty ) ) + $add_on_total, wc_get_price_decimals() ),
			'price_excluding_tax' => round( wc_get_price_excluding_tax( $product, array( 'qty' => $qty ) ) + $add_on_total_raw, wc_get_price_decimals() ),
		) );
	}
}
