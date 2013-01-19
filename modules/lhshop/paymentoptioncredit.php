<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/paymentoptioncredit.tpl.php');


$basket = erLhcoreClassModelShopUserCreditOrder::getInstance();
if (isset($_POST['ChoosePaymentMethod']))
{
	
	$definition['CheckoutGateway'] =  new ezcInputFormDefinitionElement(
		ezcInputFormDefinitionElement::OPTIONAL, 'string'
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
	
	if (count($Errors)== 0)
	{
		$basket->payment_gateway = $form->CheckoutGateway;		
			
		erLhcoreClassShop::getSession()->update($basket);				
		erLhcoreClassModule::redirect('shop/processcredits/'.$form->CheckoutGateway);
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