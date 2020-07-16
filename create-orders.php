<?php

// Copy wp mail empty plugin to mu-plugins
copy( __DIR__ . '/test-overrides.php', __DIR__ . '/wordpress/wp-content/mu-plugins/test-overrides.php' );

// Require WP Config.
require __DIR__ . '/wordpress/wp-config.php';
// Initialize WordPress.
wp();

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

while ( ! file_exists( __DIR__ . '/pending.log' ) ) {
	`php create-order.php`;
}

echo "Found webhook that sent pending status. Check pending.log\n";

$webhook->delete( true );

