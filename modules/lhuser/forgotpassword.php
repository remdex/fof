<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/forgotpassword.tpl.php');


if (isset($_POST['Forgotpassword'])) {
    
	$definition = array(
        'Email' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::REQUIRED, 'validate_email'
        )       
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    
    $Errors = array();
    
    if ( !$form->hasValidData( 'Email' ) )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/forgotpassword','Invalid e-mail address!');
    }
        
	if (count($Errors) == 0) {  
		
		$checkResult = erLhcoreClassModelUser::userEmailExists($form->Email);
		
		if ($checkResult) {
						
			$host = $_SERVER['HTTP_HOST'];	
			
			$adminEmail = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'site_admin_email' );		
			
			$userID = erLhcoreClassModelUser::fetchUserByEmail($form->Email);
			$UserData = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', $userID );
						
			$hash = erLhcoreClassModelForgotPassword::randomPassword(40);

			erLhcoreClassModelForgotPassword::setRemindHash($UserData->id,$hash);	

			// Process Mail
			erLhcoreClassPNMailAction::processMail(4,array('receiver_email' => $UserData->email, 'receiver_name' => $UserData->username),
					array('{link}'),
					array( 'http://'.$host.erLhcoreClassDesign::baseurl('user/remindpassword').'/'.$hash )
			);

			$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/forgotpasswordsent.tpl.php');		
							
		} else {
			$tpl->set('errors',array('We do not recognise that email address as one that has already registered<br/>Please enter your email address again or go to the homepage and sign up'));		
		}        
    }  else {    	
        $tpl->set('errors',$Errors);
    }  
}

$Result['content'] = $tpl->fetch();


?>