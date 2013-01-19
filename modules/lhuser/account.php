<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/account.tpl.php' );

$currentUser = erLhcoreClassUser::instance();
$UserData = $currentUser->getUserData();
                
if (isset($_POST['Update']))
{    
   $definition = array(
        'Password' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'Password1' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'Password1' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        ),
        'name' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        ),
        'Email' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'Email' ) ) {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Please enter correct e-mail address!');
    }  elseif ( $form->hasValidData( 'Email' ) && $form->Email != $UserData->email && !erLhcoreClassModelUser::userEmailExists($form->Email) ) {
    	$UserData->email = $form->Email;
    } elseif ( $form->hasValidData( 'Email' ) && $form->Email != $UserData->email) {
    	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','This e-mail is already taken!');
    }
    
    if ( $form->hasInputField( 'Password' ) && (!$form->hasInputField( 'Password1' ) || $form->Password != $form->Password1  ) ) // check for optional field
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Passwords mismatch');
    }
    
    if ( $form->hasValidData( 'name' ) && $form->name != '' )
    {
    	$UserData->username = $form->name;
    }
    
    if (count($Errors) == 0)
    {        
        
        // Update password if neccesary
        if ($form->hasInputField( 'Password' ) && $form->hasInputField( 'Password1' ) && $form->Password != '' && $form->Password1 != '')
        {
            $UserData->setPassword($form->Password);
        }
        
        erLhcoreClassUser::getSession()->update($UserData);
        $tpl->set('account_updated','done');
        
    }  else {
        $tpl->set('errors',$Errors);
    }
}

$currentUser = erLhcoreClassUser::instance();

// If already set during account update
if (!isset($UserData))
{    
    $UserData = $currentUser->getUserData();
}

$tpl->set('user',$UserData);
$Result['content'] = $tpl->fetch();



$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('user/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','My account')),
array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Account data'))
);

?>