<?php

$tpl = erLhcoreClassTemplate::getInstance('lhuser/manageprofile.tpl.php');

$UserData = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', (int)$Params['user_parameters']['user_id'] );
$tpl->set('user',$UserData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','System configuration')),

array('url' => erLhcoreClassDesign::baseurl('user/userlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Users')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','User edit').' - '.$UserData->username)


)

?>