<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/login.tpl.php');
$UserData = new erLhcoreClassModelUser();

$destination = $Params['user_parameters_unordered']['d'];

if (isset($_POST['SingUp']))
{
	$definition = array(
			'Password' => new ezcInputFormDefinitionElement(
					ezcInputFormDefinitionElement::REQUIRED, 'string'
			),
			'Password1' => new ezcInputFormDefinitionElement(
					ezcInputFormDefinitionElement::REQUIRED, 'string'
			),
			'Email' => new ezcInputFormDefinitionElement(
					ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
			),
			'name' => new ezcInputFormDefinitionElement(
					ezcInputFormDefinitionElement::REQUIRED, 'string'
			)			
	);
	
	$form = new ezcInputForm( INPUT_POST, $definition );
	$Errors = array();
	
	if ( !$form->hasValidData( 'Email' ) )
	{
		$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Wrong email address');
	}
	 
	if ( !$form->hasValidData( 'name' ) || $form->name == '')
	{
		$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter name');
	}
	
	if ( $form->hasValidData( 'Email' ) && erLhcoreClassModelUser::userEmailExists($form->Email) === true )
	{
		$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail taken!');
	}
	
	if ( !$form->hasValidData( 'Password' ) || !$form->hasValidData( 'Password1' ) || $form->Password == '' || $form->Password1 == '' || $form->Password != $form->Password1    ) // check for optional field
	{
		$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Passwords mismatch');
	}

	if (count($Errors) == 0)
	{
		$UserData->setPassword($form->Password);
		$UserData->email   = $form->Email;
		$UserData->username = $form->name;

		erLhcoreClassUser::getSession()->save($UserData);

		$GroupUser = new erLhcoreClassModelGroupUser();
		$GroupUser->group_id = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'default_user_group' ); //User group
		$GroupUser->user_id = $UserData->id;
		erLhcoreClassUser::getSession()->save($GroupUser);

		
		erLhcoreClassUser::instance()->setLoggedUserInstantly($UserData->id);
        erLhcoreClassModule::redirect('user/index');
        exit;		

	}  else {

		if ( $form->hasValidData( 'Email' ) )
		{
			$UserData->email = $form->Email;
		}

		$UserData->username = $form->name;

		$tpl->set('error_signup', $Errors);
	}
}

$tpl->set('user',$UserData);

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
            $tpl->set('error_signin',$Error);   
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
		array('title' =>  'Sign in or sign up')
);

?>