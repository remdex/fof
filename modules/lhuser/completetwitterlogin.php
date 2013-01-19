<?php

//unset($_SESSION['twitter_oauth_token']); 
//unset($_SESSION['twitter_oauth_token_secret']); 
//exit();

$tpl = erLhcoreClassTemplate::getInstance( 'lhuser/completetwitterlogin.tpl.php'); 

$consumer_key = erConfigClassLhConfig::getInstance()->getSetting( 'twitter', 'consumer_key' );
$consumer_secret = erConfigClassLhConfig::getInstance()->getSetting( 'twitter', 'consumer_secret' );

try {
	
	if (isset($_GET['denied'])) {
		
		erLhcoreClassModule::redirect('/');
		exit();	
		
	} elseif (isset($_SESSION['twitter_oauth_token']) && isset($_SESSION['twitter_oauth_token_secret'])) {	
		
	    $twitterObj = new EpiTwitter($consumer_key, $consumer_secret,  $_SESSION['twitter_oauth_token'], $_SESSION['twitter_oauth_token_secret']);
		
	} else {
		
		$twitterObj = new EpiTwitter($consumer_key, $consumer_secret); 
		
		$twitterObj->setToken($_GET['oauth_token']);  
		$token = $twitterObj->getAccessToken();  
		$twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);  
		
		$_SESSION['twitter_oauth_token'] = $token->oauth_token;  
		$_SESSION['twitter_oauth_token_secret'] = $token->oauth_token_secret; 	
			
	}
	
	$twitterInfo = $twitterObj->get('/account/verify_credentials.json'); 
		
	$oauthUserExist = erLhcoreClassModelUserOauth::findOne(array('filter' => array('twitter_user_id' => $twitterInfo->id)));
	
	if ($oauthUserExist) {
				
		$oauthUserExist->user_name = $twitterInfo->screen_name;
		$oauthUserExist->oauth_token = $_SESSION['twitter_oauth_token'];
		$oauthUserExist->oauth_token_secret = $_SESSION['twitter_oauth_token_secret'];
		$oauthUserExist->updateThis();
		
		unset($_SESSION['twitter_oauth_token']);
		unset($_SESSION['twitter_oauth_token_secret']);
		
		erLhcoreClassUser::instance()->setLoggedUserInstantly( $oauthUserExist->user_id);        
		erLhcoreClassModule::redirect('user/index');
		exit;
		
	} else {
		
		$currentUser = erLhcoreClassUser::instance();	
				
		$tpl->set('multiple_action',true);
        $tpl->set('create_account',3);
        
		if ($currentUser->isLogged()) {
            $tpl->set('map_to_current',true);
            $tpl->set('current_user',$currentUser->getUserData());
            $tpl->set('create_account',2);
        }
        
        if (isset($_POST['MapAccounts']) && isset($_POST['CreateAccount']) && $_POST['CreateAccount'] == 2) {
    
            if ($currentUser->isLogged()) {

				$oauthUserData = new erLhcoreClassModelUserOauth();
				$oauthUserData->user_id = $currentUser->getUserID();
				$oauthUserData->twitter_user_id = $twitterInfo->id;
				$oauthUserData->user_name = $twitterInfo->screen_name;
				$oauthUserData->oauth_token = $_SESSION['twitter_oauth_token'];
				$oauthUserData->oauth_token_secret = $_SESSION['twitter_oauth_token_secret'];
				$oauthUserData->saveThis();

				unset($_SESSION['twitter_oauth_token']);
				unset($_SESSION['twitter_oauth_token_secret']);
				
                erLhcoreClassUser::instance()->setLoggedUserInstantly($currentUser->getUserID());
                erLhcoreClassModule::redirect('user/index');
                exit;
                
            }
            
        // User decided to create account
        } elseif (isset($_POST['MapAccounts']) && isset($_POST['CreateAccount']) && $_POST['CreateAccount'] == 1) {

			$email = '';
        	
        	$Errors = erLhcoreClassSearchHandler::validateInputTwitter($email);
        	
        	if( count($Errors) == 0 ) {
        		
        		$UserData = new erLhcoreClassModelUser();
            	$UserData->name = $twitterInfo->name;
        		$UserData->email = $email;
				$UserData->setPassword(erLhcoreClassModelForgotPassword::randomPassword(10));
        		
        		erLhcoreClassUser::getSession()->save($UserData);        		
        		
				$GroupUser = new erLhcoreClassModelGroupUser();        
				$GroupUser->group_id = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'default_user_group' ); //User group
				$GroupUser->user_id = $UserData->id;        
				erLhcoreClassUser::getSession()->save($GroupUser);
				
				$CacheManager = erConfigClassLhCacheConfig::getInstance();
				$CacheManager->expireCache();
				
				$oauthUserData = new erLhcoreClassModelUserOauth();
				$oauthUserData->user_id = $UserData->id;
				$oauthUserData->twitter_user_id = $twitterInfo->id;
				$oauthUserData->user_name = $twitterInfo->screen_name;
				$oauthUserData->oauth_token = $_SESSION['twitter_oauth_token'];
				$oauthUserData->oauth_token_secret = $_SESSION['twitter_oauth_token_secret'];
				$oauthUserData->saveThis();
				
				unset($_SESSION['twitter_oauth_token']);
				unset($_SESSION['twitter_oauth_token_secret']);
				
				erLhcoreClassUser::instance()->setLoggedUserInstantly($UserData->id);        
				erLhcoreClassModule::redirect('user/index');
				exit;
        		
        	} else {
				$tpl->set('wrong_email',true);
    			$tpl->set('wrong_email_msg',$Errors[0]);
    			$tpl->set('create_account',1);
			}
            
        } elseif (isset($_POST['MapAccounts']) &&  $_POST['CreateAccount'] == 3) {
        	      
        	$currentUser = erLhcoreClassUser::instance();
        	      
            if ($currentUser->authenticate($_POST['Username'],$_POST['Password'])) {
            	                
				$oauthUserData = new erLhcoreClassModelUserOauth();
				$oauthUserData->user_id = $currentUser->getUserID();
				$oauthUserData->twitter_user_id = $twitterInfo->id;
				$oauthUserData->user_name = $twitterInfo->screen_name;
				$oauthUserData->oauth_token = $_SESSION['twitter_oauth_token'];
				$oauthUserData->oauth_token_secret = $_SESSION['twitter_oauth_token_secret'];
				$oauthUserData->saveThis();
                
				unset($_SESSION['twitter_oauth_token']);
				unset($_SESSION['twitter_oauth_token_secret']);
				
                erLhcoreClassUser::instance()->setLoggedUserInstantly($currentUser->getUserID());
                erLhcoreClassModule::redirect('user/index');
                exit;
                
            } else {
                $tpl->set('failed_authenticate',true);
            }
            
        }
				
	}
	
} catch(EpiTwitterException $e) {  
	
	$exceptionMmsg = 'We caught an EpiOAuthException: ';  
	echo $exceptionMmsg .= $e->getMessage();  
	//erLhcoreClassModule::redirect('user/login');
	exit();
	
} catch(Exception $e) {  
	
	$exceptionMmsg = 'We caught an unexpected Exception: ';  
	echo $exceptionMmsg .= $e->getMessage();  
	//erLhcoreClassModule::redirect('user/login');
	exit();
	
}  

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
	array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/account','Login with Twitter')),
);