<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/buycredits.tpl.php');

$credits_order = erLhcoreClassModelShopUserCreditOrder::getInstance();

if (isset($_POST['ChoosePaymentMethod']))
{
	$definition = array(
        'CreditsAmount' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'int'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'CreditsAmount' ) || $form->CreditsAmount == '' || $form->CreditsAmount == 0)
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationform','Credits amount must be greater than 0!');
    } else {
    	$credits_order->credits = $form->CreditsAmount; 
    	erLhcoreClassShop::getSession()->update( $credits_order );
    }
    
    if (count($Errors) == 0) {
    	
    	erLhcoreClassModule::redirect('shop/paymentoptioncredit');
        return ;
    	
    } else {
    	$tpl->set('errors',$Errors);
    }
}

$tpl->set('credits_order',$credits_order);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Basket'))
)
?>