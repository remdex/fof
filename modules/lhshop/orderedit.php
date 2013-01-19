<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/orderedit.tpl.php');
$Order = $Params['user_object'] ;


$tpl->set('order',$Order);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My albums')),
array('title' => ''),

);

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')),
array('url' => erLhcoreClassDesign::baseurl('shop/orderslist'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Orders list')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Order view'))

);

