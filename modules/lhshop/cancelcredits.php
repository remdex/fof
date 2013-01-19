<?php


$paymentHandler = erLhcoreClassShopPaymentHandler::fetch($Params['user_parameters']['identifier']);

$processHandler = erLhcoreClassSystem::instance()->SiteDir . 'lib/core/lhshop/paymenthandlers/'.$paymentHandler->identifier.'/handlers/'.$paymentHandler->cancel_credits_handler;

if (file_exists($processHandler)){
	require_once($processHandler);
}



