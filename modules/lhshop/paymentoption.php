<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/paymentoption.tpl.php');

$basket = erLhcoreClassModelShopBasketSession::getInstance();

if (count($basket->basket_items) == 0){
	erLhcoreClassModule::redirect('shop/basket/');
    exit;
}

if (isset($_POST['ChoosePaymentMethod']))
{
	
	$definition['CheckoutGateway'] =  new ezcInputFormDefinitionElement(
		ezcInputFormDefinitionElement::OPTIONAL, 'string'
	);
	
	$definition['Email'] =  new ezcInputFormDefinitionElement(
		ezcInputFormDefinitionElement::OPTIONAL, 'validate_email'
	);
	
	$form = new ezcInputForm( INPUT_POST, $definition );
	$Errors = array();

	if ( $form->hasValidData( 'CheckoutGateway' ) && $form->CheckoutGateway != '')
	{
		$handler = erLhcoreClassShopPaymentHandler::fetch($form->CheckoutGateway);
		if ($handler instanceof erLhcoreClassShopPaymentHandler) {
			
			if ($handler->active == false){							
				$Errors[] = 'Payment handler ';
			}
			
		} else {
			$Errors[] = 'Selected handler is not supported';
		}
		
	} else {
		$Errors[] = 'Please choose payment method!';
	}
		
	if (!$form->hasValidData( 'Email' ))
	{
		$Errors[] = 'Please enter e-mail!';
	} else {
		$basket->order->email = $form->Email;		
	}
	
	if (count($Errors)== 0)
	{
		$basket->order->payment_gateway = $form->CheckoutGateway;		
		$basket->order->initOrderItems();
		
		erLhcoreClassShop::getSession()->update($basket->order);				
		erLhcoreClassModule::redirect('shop/process/'.$form->CheckoutGateway);
        exit;
        
	} else {
		$tpl->set('errArr',$Errors);
	}
		
	
}

$tpl->set('basket',$basket);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Payment option'))
)
?>