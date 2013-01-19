<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/basket.tpl.php');

$basket = erLhcoreClassModelShopBasketSession::getInstance();
$tpl->set('basketItems',$basket->basket_items);
			

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Basket'))
)
?>