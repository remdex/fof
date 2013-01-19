<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/registration.tpl.php');
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
        'Username' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        ),
        'CaptchaCode' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'string'
        )
    );
  
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
    
    if ( !$form->hasValidData( 'Email' ) )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Wrong email address');
    }
         
    if ( !$form->hasValidData( 'Username' ) || $form->Username == '')
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter username');
    }
    
    if ( $form->hasValidData( 'Username' ) && $form->Username != '' && erLhcoreClassModelUser::userExists($form->Username) === true )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','User exists');
    }
    
    if ( $form->hasValidData( 'Email' ) && erLhcoreClassModelUser::userEmailExists($form->Email) === true )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','E-mail taken!');
    }
    
    if ( !$form->hasValidData( 'Password' ) || !$form->hasValidData( 'Password1' ) || $form->Password == '' || $form->Password1 == '' || $form->Password != $form->Password1    ) // check for optional field
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Passwords mismatch');
    }
    
    if ( !$form->hasValidData( 'CaptchaCode' ) || $form->CaptchaCode == '' || $form->CaptchaCode != $_SESSION[$_SERVER['REMOTE_ADDR']]['feedback_form'] )
    {
        $Errors[] =  'Please enter captcha';
    }
    
    if (count($Errors) == 0)
    {  
        $UserData->setPassword($form->Password);
        $UserData->email   = $form->Email;
        $UserData->username = $form->Username;
        
        erLhcoreClassUser::getSession()->save($UserData); 
        
        $GroupUser = new erLhcoreClassModelGroupUser();        
        $GroupUser->group_id = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'default_user_group' ); //User group
        $GroupUser->user_id = $UserData->id;        
        erLhcoreClassUser::getSession()->save($GroupUser);
               
        try {
            $defaultUserCategoryParent = erLhcoreClassModelGalleryCategory::fetch(erConfigClassLhConfig::getInstance()->getSetting( 'gallery_settings', 'default_gallery_category' ));
            $userCategory = new erLhcoreClassModelGalleryCategory();
            $userCategory->owner_id =  $UserData->id;
            $userCategory->parent =  $defaultUserCategoryParent->cid;
            $userCategory->name =  $form->Username;
            erLhcoreClassGallery::getSession()->save($userCategory);               
            $userCategory->clearCategoryCache();
        } catch (Exception $e) { // Perhaps administrator deleted default gallery category
            // Do nothing
        }
        
        erLhcoreClassUser::instance()->setLoggedUserInstantly($UserData->id);
        erLhcoreClassModule::redirect('user/index');
        exit;
    
        
    }  else {
        
        if ( $form->hasValidData( 'Email' ) )
        {
            $UserData->email = $form->Email;
        }
        
        $UserData->username = $form->Username;
        
        $tpl->set('errArr',$Errors);
    }
}

$tpl->set('user',$UserData);
$Result['content'] = $tpl->fetch();

?>