<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/associatedlogins.tpl.php' );

$currentUser = erLhcoreClassUser::instance();
$UserData = $currentUser->getUserData();

$tpl->set('user',$UserData);
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/index','My account')));

?>