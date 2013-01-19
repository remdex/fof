<?php

// Payment handler definition
$definition = array(
	'identifier' => 'paypal_handler',
	'name'		 => 'Checkout using Paypal',
	'author'     => 'Remigijus Kiminas',
	'process_handler' 	=> 'process.php',
	'processcredit_handler' => 'processcredit.php',
	'review_handler' 	=> 'review.php',
	'review_credits_handler' 	=> 'review_credits.php',
	'accept_handler' 	=> 'accept.php',
	'error_handler' 	=> 'error.php',
	'cancel_handler' 	=> 'cancel.php',
	'cancel_credits_handler' 	=> 'cancelcredits.php',
	'allow_credit_buy' 	=> true,
	'handler_classname' => 'erLhcoreClassShopPaymentHandlerPaypal',
	'settings'	 			=> array(
		'api_username' => array('type' => 'text','name' => 'API username'),
		'api_password' => array('type' => 'text','name' => 'API Password'),
		'signature'    => array('type' => 'text','name' => 'Signature'),
		'api_subject'  => array('type' => 'text','name' => 'Api subject'),
		'sandboxmode'  => array('type' => 'checkbox','name' => 'Sandbox mode'),
		'payment_type' => array('type' => 'text','name' => 'Payment type (preffered Sale)'),
	)
);

?>