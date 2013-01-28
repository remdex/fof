<?php

if ( isset($_POST['Cancel_account']) ) {        
    erLhcoreClassModule::redirect('user/userlist');
    exit;
} 

$tpl = erLhcoreClassTemplate::getInstance('lhuser/edit.tpl.php');

$UserData = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', (int)$Params['user_parameters']['user_id'] );

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
    
    if ( $form->hasValidData( 'Email' ) && $form->Email != $UserData->email && erLhcoreClassModelUser::userEmailExists($form->Email) === true )
    {
    	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail taken!');
    }
    
	if ( $form->hasInputField( 'Password' ) && (!$form->hasInputField( 'Password1' ) || $form->Password != $form->Password1  ) ) // check for optional field
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Passwords mismatch');
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
        // Update password if neccesary
        if ($form->hasInputField( 'Password' ) && $form->hasInputField( 'Password1' ) && $form->Password != '' && $form->Password1 != '')
        {
            $UserData->setPassword($form->Password);
        }
        
        $UserData->email   = $form->Email;
        $UserData->username = $form->name;
        
        erLhcoreClassUser::getSession()->update($UserData);
        
        erLhcoreClassModelGroupUser::removeUserFromGroups($UserData->id);
                
        foreach ($UserData->user_groups_id as $group_id) {
        	$groupUser = new erLhcoreClassModelGroupUser();
        	$groupUser->group_id = $group_id;
        	$groupUser->user_id = $UserData->id;
        	$groupUser->saveThis();
        }
        
        $CacheManager = erConfigClassLhCacheConfig::getInstance();
        $CacheManager->expireCache();
        
        erLhcoreClassModule::redirect('user/userlist');
        return ;
        
    }  else {
        $tpl->set('errors',$Errors);
    }
}


$tpl->set('user',$UserData);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','System configuration')),

array('url' => erLhcoreClassDesign::baseurl('user/userlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Users')),

array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','User edit').' - '.$UserData->username)


)

?>