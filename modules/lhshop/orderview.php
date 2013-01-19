<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/orderview.tpl.php');
$Order = $Params['user_object'] ;




$tpl->set('order',$Order);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),

array('url' => erLhcoreClassDesign::baseurl('gallery/albumedit'), 'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My albums')),
array('title' => ''),


);

