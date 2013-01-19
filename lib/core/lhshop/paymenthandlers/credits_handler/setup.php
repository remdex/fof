<?php

// Payment handler definition
$definition = array(
	'identifier' => 'credits_handler',
	'name'		 => 'Credits based',
	'author'     => 'Remigijus Kiminas',
	'process_handler' 	=> 'process.php',
	'accept_handler' 	=> 'accept.php',
	'allow_credit_buy' 	=> false,
	'handler_classname' => 'erLhcoreClassShopPaymentHandlerCredits',
	'settings'	 			=> array(
		
	)
);

?>