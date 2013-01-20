<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/new.tpl.php');

$UserData = new erLhcoreClassModelUser();

if (isset($_POST['Update_account']))
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
				),
				'SendInvite' => new ezcInputFormDefinitionElement(
						ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
				),
				'UserDisabled' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
    			), 
				'DefaultGroup' => new ezcInputFormDefinitionElement(
						ezcInputFormDefinitionElement::OPTIONAL, 'int',
       							null,
       							FILTER_REQUIRE_ARRAY
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
	
	if ( $form->hasValidData( 'DefaultGroup' ) ) {
		$UserData->user_groups_id = $form->DefaultGroup;
	} else {
		$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please choose default user group');
	}
	
	if ( $form->hasValidData( 'UserDisabled' ) && $form->UserDisabled == true )
	{
		$UserData->disabled = 1;
	} else {
		$UserData->disabled = 0;
	}
	
    if (count($Errors) == 0)
    {     
    	$UserData->username = $form->name;
    	$UserData->setPassword($form->Password);
    	$UserData->email   = $form->Email;
    	    	
    	if ($form->hasValidData( 'SendInvite') && $form->SendInvite == true) {
	    	$currentUser = erLhcoreClassUser::instance();
	    	$UserDataCurrent = $currentUser->getUserData();
	    	
	    	erLhcoreClassPNMailAction::processMail(2,array('receiver_email' => $UserData->email, 'receiver_name' => $UserData->username),
	    			array('{registered_user}','{email}','{password}','{user_sender}'),
	    			array($UserData->username,$UserData->email,$form->Password,$UserDataCurrent->username)
	    	);    	
    	}
    	
        erLhcoreClassUser::getSession()->save($UserData);

        foreach ($UserData->user_groups_id as $group_id) {
        	$groupUser = new erLhcoreClassModelGroupUser();
        	$groupUser->group_id = $group_id;
        	$groupUser->user_id = $UserData->id; 
        	$groupUser->saveThis();
        }
              
        $CacheManager = erConfigClassLhCacheConfig::getInstance();
        $CacheManager->expireCache();
        
        erLhcoreClassModule::redirect('user/userlist');
        exit;
        
    }  else {
        
        if ( $form->hasValidData( 'Email' ) )
        {
            $UserData->email = $form->Email;
        }
               
        $UserData->username = $form->name;

        if ($form->hasValidData( 'SendInvite') && $form->SendInvite == true) {
        	$tpl->set('sendinvite', $form->SendInvite);
        }
        
        $tpl->set('errors',$Errors);
    }
}


$tpl->set('user',$UserData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','System configuration')),

array('url' => erLhcoreClassDesign::baseurl('user/userlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Users')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','New user'))

)

?>