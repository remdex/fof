<?php


$tpl = erLhcoreClassTemplate::getInstance('lhuser/finishgoogleauth.tpl.php');


/**
 * Require the OpenID consumer code.
 */
require_once "lib/core/lhexternal/Auth/OpenID/Consumer.php";

/**
 * Require the "file store" module, which we'll need to store
 * OpenID information.
 */
require_once "lib/core/lhexternal/Auth/OpenID/DatabaseConnection.php";
require_once "lib/core/lhexternal/Auth/OpenID/MySQLStore.php";
require_once "lib/core/lhexternal/Auth/lhmysqlstore.php";

// We need to fix server_url
// https://www.google.com/accounts/o8/ud to
// https://www.google.com/accounts/o8/id    
$db = ezcDbInstance::get();

$stmt = $db->prepare("UPDATE lh_oid_associations SET server_url = :param1 WHERe server_url = :param2"); 
$stmt->bindValue( ':param1','https://www.google.com/accounts/o8/id');    
$stmt->bindValue( ':param2','https://www.google.com/accounts/o8/ud');   
$stmt->execute();  

/**
 * Require the Simple Registration extension API.
 */
require_once "lib/core/lhexternal/Auth/OpenID/SReg.php";
require_once "lib/core/lhexternal/Auth/OpenID/AX.php";

/**
 * Require the PAPE extension module.
 */
require_once "lib/core/lhexternal/Auth/OpenID/PAPE.php";

global $pape_policy_uris;
$pape_policy_uris = array(
			  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			  PAPE_AUTH_MULTI_FACTOR,
			  PAPE_AUTH_PHISHING_RESISTANT
			  );
			  
function getOpenIDURL() {    
    return 'https://www.google.com/accounts/o8/id';
}

function getStore() {             
     return new Auth_OpenID_MySQLStore(new lhMysqlStore(),'lh_oid_associations','lh_oid_nonces');     
}

function &getConsumer() {
    /**
     * Create a consumer object using the store object created
     * earlier.
     */    
    $store = getStore();
    $consumer = new Auth_OpenID_Consumer($store);
    return $consumer;
}

function getScheme() {
    $scheme = 'http';
    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
        $scheme .= 's';
    }
    return $scheme;
}

function getReturnTo() {
    return getScheme().'://'.$_SERVER['SERVER_NAME']. erLhcoreClassDesign::baseurl('user/finishgoogleauth');    
}
	
// Complete the authentication process using the server's response.
$thispage = getReturnTo();
$consumer = getConsumer();
$response = $consumer->complete($thispage);

if ($response->status == Auth_OpenID_CANCEL) {
    // This means the authentication was cancelled.
    $msg = 'Verification cancelled.';
    $tpl->set('msg',$msg);    
} else if ($response->status == Auth_OpenID_FAILURE) {
    $msg = "OpenID authentication failed: " . $response->message;
    $tpl->set('msg',$msg);
} else if ($response->status == Auth_OpenID_SUCCESS) {
    // This means the authentication succeeded.
    $openid = $response->identity_url;
    $esc_identity = htmlspecialchars($openid, ENT_QUOTES);
    $success = sprintf('You have successfully verified ' .
                       '<a href="%s">%s</a> as your identity.',
                       $esc_identity, $esc_identity);

    if ($response->endpoint->canonicalID) {
        $success .= '  (XRI CanonicalID: '.$response->endpoint->canonicalID.') ';
    }

    $ax = new Auth_OpenID_AX_FetchResponse();
    $obj = $ax->fromSuccessResponse($response);

    $stmt = $db->prepare("UPDATE lh_oid_associations SET server_url = :param1 WHERe server_url = :param2"); 
    $stmt->bindValue( ':param1','https://www.google.com/accounts/o8/ud');    
    $stmt->bindValue( ':param2','https://www.google.com/accounts/o8/id');   
    $stmt->execute(); 
    
    // Check does OpenID exists already if yes, just login user
    $oidMapList = erLhcoreClassModelOidMap::getList(array('filter' => array('open_id' => $response->identity_url)));   
    $oidMap = false;
    
    if  (count($oidMapList) == 1){
        $oidMap = current($oidMapList);
    }
    
    if ($oidMap instanceof erLhcoreClassModelOidMap){ // This user is already mapped just login to account
        erLhcoreClassUser::instance()->setLoggedUserInstantly($oidMap->user_id);        
        erLhcoreClassModule::redirect('user/index');
        exit;        
    } elseif ( ($user_id = erLhcoreClassModelUser::fetchUserByEmail($obj->data['http://axschema.org/contact/email'][0])) !== false) { // User exists suggest to map accounts    
                
        // If user is logged just map accounts
        $currentUser = erLhcoreClassUser::instance();
        if ($currentUser->isLogged() && $user_id == $currentUser->getUserID()) {
            $oidMap = new erLhcoreClassModelOidMap();
            $oidMap->user_id = $user_id;
            $oidMap->open_id = $response->identity_url;
            $oidMap->open_id_type = erLhcoreClassModelOidMap::OPEN_ID_GOOGLE;
            $oidMap->email = $obj->data['http://axschema.org/contact/email'][0];
            $oidMap->saveThis();
            erLhcoreClassUser::instance()->setLoggedUserInstantly($user_id);        
            erLhcoreClassModule::redirect('user/index');
            exit;
       } 
            
       $_SESSION['open_id_identity_url'] = $response->identity_url;
       $_SESSION['open_id_email'] = $obj->data['http://axschema.org/contact/email'][0];
       $_SESSION['open_id_type'] = erLhcoreClassModelOidMap::OPEN_ID_GOOGLE ;       
       erLhcoreClassModule::redirect('user/mapaccounts');
       exit;
    } elseif (isset($obj->data['http://axschema.org/contact/email'][0])) {
       $_SESSION['open_id_identity_url'] = $response->identity_url;
       $_SESSION['open_id_type'] = erLhcoreClassModelOidMap::OPEN_ID_GOOGLE ; 
       $_SESSION['open_id_email'] = $obj->data['http://axschema.org/contact/email'][0];      
       erLhcoreClassModule::redirect('user/mapaccounts');
       exit;
    }
        
     
       
    $tpl->set('success',$success);
}
//http://www.plaxo.com/api/openid_recipe
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','Authentifion with google')));

?>