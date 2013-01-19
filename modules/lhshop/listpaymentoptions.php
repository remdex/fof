<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/listpaymentoptions.tpl.php');

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));

?>