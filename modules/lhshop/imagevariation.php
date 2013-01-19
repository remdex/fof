<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/imagevariation.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Images variations sizes'))
)
?>