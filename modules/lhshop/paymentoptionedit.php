<?php 

$tpl = erLhcoreClassTemplate::getInstance('lhshop/paymentoptionedit.tpl.php');

$paymentHandler = erLhcoreClassShopPaymentHandler::fetch($Params['user_parameters']['identifier']);


if (isset($_POST['UpdatePaymentSetting']))
{
	$definition = array();

	$definition['ActiveHandler'] =  new ezcInputFormDefinitionElement(
	ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
	);

	foreach ($paymentHandler->settings as $key => $setting) {
		$definition[$paymentHandler->identifier.'_'.$key] =  new ezcInputFormDefinitionElement(
		ezcInputFormDefinitionElement::OPTIONAL, $setting['type'] == 'text' ? 'string' : 'boolean'
		);
	}

	$form = new ezcInputForm( INPUT_POST, $definition );
	$Errors = array();

	//Assignment
	foreach ($paymentHandler->settings as $key => $setting) {

		if ( $form->hasValidData( $paymentHandler->identifier.'_'.$key ) )
		{
			$paymentHandler->setSettingValue($key,$form->{$paymentHandler->identifier.'_'.$key});
		} else {
			$paymentHandler->setSettingValue($key,false);
		}
	}

	if ( $form->hasValidData( 'ActiveHandler' ) ) {
		$paymentHandler->setSettingValue('active',true);
	} else {
		$paymentHandler->setSettingValue('active',false);
	}

}

$tpl->set('payment_handler',$paymentHandler);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('url' => erLhcoreClassDesign::baseurl('shop/listpaymentoptions'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Payment options')),
array('title' => $paymentHandler->name)
)


?>