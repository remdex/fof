<?php

require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Profile/Handler/Array.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Profile/API.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/BasicAmountType.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/SetExpressCheckoutRequestType.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/SetExpressCheckoutRequestDetailsType.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/SetExpressCheckoutResponseType.php';

require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/GetExpressCheckoutDetailsRequestType.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/GetExpressCheckoutDetailsResponseDetailsType.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/GetExpressCheckoutDetailsResponseType.php';
		
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/GetExpressCheckoutDetailsResponseDetailsType.php';
require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/GetExpressCheckoutDetailsResponseType.php';

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/handlers/paypal_handler/review.tpl.php');
$tpl->set('payment_handler',$paymentHandler);

if (isset($_POST['ConfirmPayment']))
{
	$tpl->set('mode','pay');	
	require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/PaymentDetailsType.php';
	require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/DoExpressCheckoutPaymentRequestType.php';
	require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/DoExpressCheckoutPaymentRequestDetailsType.php';
	require_once 'lib/core/lhshop/paymenthandlers/paypal_handler/php-sdk/lib/PayPal/Type/DoExpressCheckoutPaymentResponseType.php';
	
	$handler = ProfileHandler_Array::getInstance(array(
            'username' => $paymentHandler->getSettingValue('api_username')->value,
            'certificateFile' => null,
            'subject' => null,
            'environment' => $paymentHandler->getSettingValue('sandboxmode')->value == 1 ? 'sandbox' : 'live'  ));
            
	$pid = ProfileHandler::generateID();
	
	$profile = new APIProfile($pid, $handler);
	     
	$profile->setAPIUsername($paymentHandler->getSettingValue('api_username')->value);
	$profile->setAPIPassword($paymentHandler->getSettingValue('api_password')->value);
	$profile->setSignature($paymentHandler->getSettingValue('signature')->value);  
	$profile->setEnvironment($paymentHandler->getSettingValue('sandboxmode')->value == 1 ? 'sandbox' : 'live'); 
	
	$token = $Params['user_parameters_unordered']['token'];
	$paymentAmount = $Params['user_parameters_unordered']['paymentAmount'];
	$paymentType = $Params['user_parameters_unordered']['paymentType'];
	$currencyCodeType = $Params['user_parameters_unordered']['currencyCodeType'];
	$payerID = $Params['user_parameters_unordered']['payerID'];
	
	$ec_details = PayPal::getType('DoExpressCheckoutPaymentRequestDetailsType');
	
	$ec_details->setToken($token);
	$ec_details->setPayerID($payerID);
	$ec_details->setPaymentAction($paymentType);
			
	$amt_type =& PayPal::getType('BasicAmountType');
	$amt_type->setattr('currencyID', $currencyCodeType);
	$amt_type->setval($paymentAmount, 'iso-8859-1');  
	
	$payment_details =& PayPal::getType('PaymentDetailsType');
	$payment_details->setOrderTotal($amt_type);
	
	$ec_details->setPaymentDetails($payment_details);
	
	$ec_request = PayPal::getType('DoExpressCheckoutPaymentRequestType');
	$ec_request->setDoExpressCheckoutPaymentRequestDetails($ec_details);
	
	$caller = PayPal::getCallerServices($profile);
		
	$basket = erLhcoreClassModelShopBasketSession::getInstance();
	
	// If ammounts is the same
	if (round((float)$paymentAmount,2) == round((float)$basket->order->amount,2))
	{		
		// Execute SOAP request
		$response = $caller->DoExpressCheckoutPayment($ec_request);
					
		$ack = $response->getAck();
		
		switch($ack) {
		  case 'Success':
		  case 'SuccessWithWarning':
		  			  	
			$basket->order->status = erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED;
			$basket->order->generateDownloadHashLink();
					
			erLhcoreClassShop::getSession()->update($basket->order);	
			erLhcoreClassShopMail::sendOrderMail($basket->order);
			
			$basket->removeThis(); // Cleanup basket
		  		  	
		     // Good to break out;
		     break;
		     
		  default:
		  	$_SESSION['error_shop'] = $response;
	        erLhcoreClassModule::redirect('/shop/error/paypal_handler');
	        exit;
		}
		
		// Marshall data out of response
		$details = $response->getDoExpressCheckoutPaymentResponseDetails();
		$payment_info = $details->getPaymentInfo();
		$tran_ID = $payment_info->getTransactionID();
		
		$amt_obj = $payment_info->getGrossAmount();
		$amt = $amt_obj->_value;
		$currency_cd = $amt_obj->_attributeValues['currencyID'];
		$display_amt = $currency_cd.' '.$amt;
	
		$tpl->set('display_amt',$display_amt);
		$tpl->set('tran_ID',$tran_ID);
	}
	
	
} else {




$ec_request = PayPal::getType('GetExpressCheckoutDetailsRequestType');
$ec_request->setToken($_GET['token']);

$handler = ProfileHandler_Array::getInstance(array(
            'username' => $paymentHandler->getSettingValue('api_username')->value,
            'certificateFile' => null,
            'subject' => null,
            'environment' => $paymentHandler->getSettingValue('sandboxmode')->value == 1 ? 'sandbox' : 'live'  ));
            
$pid = ProfileHandler::generateID();

$profile = new APIProfile($pid, $handler);
     
$profile->setAPIUsername($paymentHandler->getSettingValue('api_username')->value);
$profile->setAPIPassword($paymentHandler->getSettingValue('api_password')->value);
$profile->setSignature($paymentHandler->getSettingValue('signature')->value);  
$profile->setEnvironment($paymentHandler->getSettingValue('sandboxmode')->value == 1 ? 'sandbox' : 'live'); 

$caller =& PayPal::getCallerServices($profile);

// Execute SOAP request
$response = $caller->GetExpressCheckoutDetails($ec_request);

$ack = $response->getAck();

switch($ack) {
  case 'Success':
  case 'SuccessWithWarning':
     // Good to break out;
     break;
     
  default:
  	 $_SESSION['error_shop'] = $response;
     erLhcoreClassModule::redirect('/shop/error/paypal_handler');
     exit;
}
      
$resp_details = $response->getGetExpressCheckoutDetailsResponseDetails();

$tpl->set('resp_details',$resp_details);
$tpl->set('token',$_GET['token']);
$tpl->set('mode','review');
$tpl->set('currencyCodeType',$_GET['currencyCodeType']);
$tpl->set('paymentType',$_GET['paymentType']);

}


$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));

