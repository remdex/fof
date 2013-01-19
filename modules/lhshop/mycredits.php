<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/mycdredits.tpl.php');

$currentUser = erLhcoreClassUser::instance();

$tpl->set('credits',erLhcoreClassModelShopUserCredit::fetch($currentUser->getUserID()));

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/mycredits','My credits')));

?>