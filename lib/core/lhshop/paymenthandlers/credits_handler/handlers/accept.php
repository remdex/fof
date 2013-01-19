<?php


$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/handlers/credits_handler/accept.tpl.php');
$tpl->set('payment_handler',$paymentHandler);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));

