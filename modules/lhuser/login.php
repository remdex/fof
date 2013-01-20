<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/login.tpl.php');
$UserData = new erLhcoreClassModelUser();
$tpl->set('user',$UserData);

$destination = $Params['user_parameters_unordered']['d'];
$currentUser = erLhcoreClassUser::instance();

if ( !empty($destination) ) {
	$destination = urldecode($destination);
}

if ( $currentUser->isLogged() && $destination != '' ) {
	header('Location: '.base64_decode($destination));
	exit;
}

$tpl->set('d',$destination);
$desinationURL = '';

if (isset($_POST['Login']))
{
	if ( isset($_POST['DestinationURL']) && $_POST['DestinationURL'] != '' ) {
		$desinationURL = base64_decode($_POST['DestinationURL']);
		$tpl->set('d',$_POST['DestinationURL']);
	}
	
    if (!$currentUser->authenticate($_POST['email'],$_POST['pass']))
    {     
            $Error = erTranslationClassLhTranslation::getInstance()->getTranslation('user/login','Incorrect e-mail or password');
            $tpl->set('errors',array($Error));   
    } else {
        
    	if ( $desinationURL == '' ) {
    		erLhcoreClassModule::redirect();
    	} else {
    		header('Location: '.$desinationURL);
    	}
    	exit;    	
    }    
}

$Result['content'] = $tpl->fetch();

$pagelayout = erConfigClassLhConfig::getInstance()->getOverrideValue('site','login_pagelayout');
   
if ($pagelayout != null)
$Result['pagelayout'] = $pagelayout;

$Result['path'] = array(
		array('title' =>  'Sign in')
);

?>