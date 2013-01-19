<?php

// Payment handler definition
$definition = array(
	'identifier' => 'mokejimailt_macro',
	'name'		 => 'Mokejimai.lt makro mokėjimas',
	'author'     => 'Remigijus Kiminas',
	'process_handler' 		=> 'process.php',
	'processcredit_handler' => 'processcredit.php',
	'callback_handler' 		=> 'callback.php',
	'accept_handler' 		=> 'accept.php',
	'allow_credit_buy' 		=> true,
	'handler_classname'		=> 'erLhcoreClassShopPaymentHandlerMokejimaiMakro',
	'settings'	 			=> array(
		'projectid' 		=> array('type' => 'text','name' => 'Project ID'), // Project ID
		'currency' 			=> array('type' => 'text','name' => 'Currency, ex. LTL'), // 
		'country' 			=> array('type' => 'text','name' => 'Country, ex. LT'), //
		'test' 				=> array('type' => 'checkbox','name' => 'Are we in testing mode'), //
		'lang' 				=> array('type' => 'checkbox','name' => 'Language, ex. LIT'), //
		'sign_password' 	=> array('type' => 'text','name' => 'Sign password'), //
		'pay_text' 			=> array('type' => 'text','name' => 'Pay text'), //
		'minimum_amount' 	=> array('type' => 'text','name' => 'Minimum amount')
	)
);

?>