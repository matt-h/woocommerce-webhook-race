<?php

// Copy wp mail empty plugin to mu-plugins
copy( __DIR__ . '/test-overrides.php', __DIR__ . '/wordpress/wp-content/mu-plugins/test-overrides.php' );

// Require WP Config.
require __DIR__ . '/wordpress/wp-config.php';
// Initialize WordPress.
wp();


// Load WooCommerce Test Helpers.
$woo_commerce_test_dir = __DIR__ . '/woocommerce/tests/legacy';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-product.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-coupon.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-fee.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-shipping.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-customer.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-order.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-shipping-zones.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-payment-token.php';
require_once $woo_commerce_test_dir . '/framework/helpers/class-wc-helper-settings.php';


// Create WebHook.
$webhook = new WC_Webhook();
$webhook->set_props(
	array(
		'status'       => 'active',
		'name'         => 'Testing webhook',
		'user_id'      => 1,
		'delivery_url' => 'http://localhost:8080',
		'secret'       => 'secret',
		'topic'        => 'order.updated',
		'api_version'  => 2,
	)
);
$webhook->save();
wc_load_webhooks( 'active' );

$i = 0;
while ( ! file_exists( __DIR__ . '/pending.log' ) ) {
	// Create an order for testing.
	$wc_order = WC_Helper_Order::create_order();

	// usleep( 100 ); // Add a short sleep here if you can't get it to reproduce the bug.

	// Mark payment as complete which updates status to processing.
	$wc_order->payment_complete();
	$i++;
}

echo "Found webhook that sent pending status. Check pending.log\n";

$webhook->delete( true );

