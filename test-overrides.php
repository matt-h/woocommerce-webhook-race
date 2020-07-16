<?php

// WP Mail plugin that does nothing.
function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
	return true;
}

// Allow localhost in the webhook url.
add_filter( 'http_request_host_is_external', '__return_true' );
