<?php

$basket = erLhcoreClassModelShopBasketSession::getInstance();


if (isset($_POST['ConfirmOrder']))
{
	$basket = erLhcoreClassModelShopBasketSession::getInstance();
	$basket->order->status = erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED;
	$basket->order->generateDownloadHashLink();
	$basket->order->decreaseCredits();
	
	erLhcoreClassShop::getSession()->update($basket->order);	
	erLhcoreClassShopMail::sendOrderMail($basket->order);
	
	$basket->removeThis(); // Cleanup basket
	
	erLhcoreClassModule::redirect('shop/accept/credits_handler');
    exit ;		
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/handlers/credits_handler/process.tpl.php');
$tpl->set('payment_handler',$paymentHandler);
$tpl->set('basket',$basket);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));

