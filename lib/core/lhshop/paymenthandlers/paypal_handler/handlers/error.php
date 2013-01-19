<?php


$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/handlers/paypal_handler/error.tpl.php');
$tpl->set('payment_handler',$paymentHandler);
$tpl->set('error',$_SESSION['error_shop']);
unset($_SESSION['error_shop']);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));
