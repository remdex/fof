<?php

$tpl = erLhcoreClassTemplate::getInstance('lhshop/settingedit.tpl.php');

// If already set during account update
$ConfigData = erLhcoreClassShop::getSession()->load( 'erLhcoreClassModelShopBaseSetting', $Params['user_parameters']['config_id'] );

if (isset($_POST['UpdateConfig']))
{
	
	$ConfigData->value = $_POST['ValueParam'];		
	erLhcoreClassShop::getSession()->update( $ConfigData );
	$tpl->set('data_updated',true);
}

$tpl->set('systemconfig',$ConfigData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('url' => erLhcoreClassDesign::baseurl('shop/settinglist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Shop config list')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settingedit','Edit'))
)

?>