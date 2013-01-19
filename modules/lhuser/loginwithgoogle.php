<?php

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
   return getScheme().'://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurl('user/finishgoogleauth');
}

function getTrustRoot() {   
    return getScheme().'://'.$_SERVER['HTTP_HOST']. erLhcoreClassDesign::baseurldirect('/');
}


$openid = getOpenIDURL();
$consumer = getConsumer();

// Begin the OpenID authentication process.
$auth_request = $consumer->begin($openid);

// No auth request means we can't begin OpenID.
if (!$auth_request) {
    displayError("Authentication error; not a valid OpenID.");
}


// Create attribute request object
// See http://code.google.com/apis/accounts/docs/OpenID.html#Parameters for parameters
// Usage: make($type_uri, $count=1, $required=false, $alias=null)
$attribute = array();
$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email',2,1, 'email');

// Create AX fetch request
$ax = new Auth_OpenID_AX_FetchRequest;

// Add attributes to AX fetch request
foreach($attribute as $attr){
        $ax->add($attr);
}

// Add AX fetch request to authentication request
$auth_request->addExtension($ax);

$policy_uris = array(
			  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
			  PAPE_AUTH_MULTI_FACTOR,
			  PAPE_AUTH_PHISHING_RESISTANT
			  );

$pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
if ($pape_request) {
    $auth_request->addExtension($pape_request);
}

// Redirect the user to the OpenID server for authentication.
// Store the token for this authentication so we can verify the
// response.

// For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
// form to send a POST request to the server.
if ($auth_request->shouldSendRedirect()) {
    $redirect_url = $auth_request->redirectURL(getTrustRoot(),
                                               getReturnTo());

    // If the redirect URL can't be built, display an error
    // message.
    if (Auth_OpenID::isFailure($redirect_url)) {
        displayError("Could not redirect to server: " . $redirect_url->message);
    } else {
        // Send redirect.
        header("Location: ".$redirect_url);
    }
} else {
    // Generate form markup and render it.
    $form_id = 'openid_message';
    /*$form_html = $auth_request->htmlMarkup(getTrustRoot(), getReturnTo(),
                                           false, array('id' => $form_id));

    // Display an error if the form markup couldn't be generated;
    // otherwise, render the HTML.
    if (Auth_OpenID::isFailure($form_html)) {
        displayError("Could not redirect to server: " . $form_html->message);
    } else {
        print $form_html;
    }*/
        
    $form_id = 'openid_message';
    $form_html = $auth_request->formMarkup(getTrustRoot(), getReturnTo(),
                                           false, array('id' => $form_id));

    // Display an error if the form markup couldn't be generated;
    // otherwise, render the HTML.
    if (Auth_OpenID::isFailure($form_html)) {
        displayError("Could not redirect to server: " . $form_html->message);
    } else {      
        echo json_encode(array('error' => 'false','result' => $form_html));
    }    
    
}
exit;