<?php

$json = file_get_contents( 'php://input' );
$data = json_decode( $json );

if ( ! empty( $data ) ) {
	file_put_contents( __DIR__ . '/all_webhooks.log', json_encode( $data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND );

	if ( 'processing' !== $data->status ) {
		// Caught a pending order.
		file_put_contents( __DIR__ . '/pending.log', json_encode( $data, JSON_PRETTY_PRINT) . "\n", FILE_APPEND );
	}
}

