<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/remindpassword.tpl.php');

$msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','Hash not found or was used already');

$hash = $Params['user_parameters']['hash'];

if ($hash != '') {

	$hashData = erLhcoreClassModelForgotPassword::checkHash($hash);

	if ($hashData) {
						
		$UserData = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', (int)$hashData['user_id'] );
			
		if ($UserData) {
			
			$password = erLhcoreClassModelForgotPassword::randomPassword(10);
			$UserData->setPassword($password);
				
			erLhcoreClassUser::getSession()->update($UserData);
			
			// Process Mail
			erLhcoreClassPNMailAction::processMail(5,array('receiver_email' => $UserData->email, 'receiver_name' => $UserData->username),
					array('{new_password}'),
					array( $password )
			);
			
			$msg = erTranslationClassLhTranslation::getInstance()->getTranslation('user/remindpassword','A new password has been sent to you by email');
			
			erLhcoreClassModelForgotPassword::deleteHash($hashData['id']);
			
		}
	}	
} 

$tpl->set('msg',$msg);

$Result['content'] = $tpl->fetch();


?>