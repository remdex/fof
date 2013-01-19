<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/profile.tpl.php' );

try {
    $user = erLhcoreClassModelUser::fetch($Params['user_parameters']['user_id']);
} catch (Exception $e) {
    erLhcoreClassModule::redirect('/');
    exit;
}
$profile = $user->profile;

$tpl->set('user',$user);
$tpl->set('profile',$profile);

$Result['content'] = $tpl->fetch();
if ($profile->name != '' || $profile->surname != '') {
    $Result['path'] = array(array('title' => $profile->name.' '.$profile->surname));
} else {
    $Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/profile','User profile')));
}