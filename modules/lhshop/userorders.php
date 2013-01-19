<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/userorders.tpl.php');


$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelShopOrder::getListCount(array('filter' => array('user_id' => $Params['user_parameters']['user_id']),'filterin' => array('status' => array(1,2))));
$pages->serverURL = erLhcoreClassDesign::baseurl('shop/userorders');
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('user_id',$Params['user_parameters']['user_id']);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('url' => erLhcoreClassDesign::baseurl('shop/orderslist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Orders list')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','User orders list'))

);

?>