<?php

if (isset($_POST['buyersemail']))
{			
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
       
   // SetExpressCheckout handling
   $serverName = $_SERVER['SERVER_NAME'];

   $url='http://'.$serverName.erLhcoreClassDesign::baseurl('shop/review/paypal_handler');
   
   $currencyCodeType=$paymentHandler->basket->order->currency;
      
   // paymentType is ActionCodeType in ASP SDK
   $paymentType=$paymentHandler->getSettingValue('payment_type')->value;
	      
   $returnURL = $url.'/?paymentAmount='.$paymentHandler->basket->order->amount.'&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType;
   $cancelURL = 'http://'.$serverName.erLhcoreClassDesign::baseurl('shop/cancel/paypal_handler');

   $ec_request =& PayPal::getType('SetExpressCheckoutRequestType');

   //Setting up URL information.
   $ec_details =& PayPal::getType('SetExpressCheckoutRequestDetailsType');
   $ec_details->setReturnURL($returnURL);
   $ec_details->setCancelURL($cancelURL);
   $ec_details->setCallbackTimeout('4');
   $ec_details->setBuyerEmail($paymentHandler->basket->order->email);
   $ec_details->setPaymentAction($paymentType);
   $ec_details->setNoShipping(1);
   //$ec_details->setcpp_header_image('http://img91.imageshack.us/img91/8738/paypalsdk.jpg');  
	   
   //Setting up OrderTotal.
   $amt_type =& PayPal::getType('BasicAmountType');
   $amt_type->setattr('currencyID', $currencyCodeType);
   $amt_type->setval($paymentHandler->basket->order->amount, 'iso-8859-1');
   $ec_details->setOrderTotal($amt_type);

   	
	$paymentDetailsType = &PayPal::getType('PaymentDetailsType');
	
	$itemsOrderArray = array();
	
	$keyCounter = 0;	
	foreach ($paymentHandler->basket->order->order_items as $key => $orderItem)
	{
		$paymentDetailsItem = PayPal::getType('PaymentDetailsItemType');
		$paymentDetailsItem->setName($orderItem->image_variation->name.' - '.$orderItem->image->name_user);
		$paymentDetailsItem->setQuantity(1, 'iso-8859-1');
		$paymentDetailsItem->setAmount($orderItem->price, 'iso-8859-1');
		$itemsOrderArray['PaymentDetailsItem'.str_pad($key,2,'0')] = $paymentDetailsItem;		
		$keyCounter++;
	}
	
	$paymentDetailsType->setPaymentDetailsItem($itemsOrderArray);
	
	//Setting up OrderTotal on PaymentDetails.
	$itemTotal_type =& PayPal::getType('BasicAmountType');
   	$itemTotal_type->setattr('currencyID', $currencyCodeType);
   	$itemTotal_type->setval($paymentHandler->basket->order->amount/*$itemamt*/, 'iso-8859-1');
   	$paymentDetailsType->setItemTotal($itemTotal_type);
	
	
	$ec_details->setPaymentDetails($paymentDetailsType);

   	$ec_request->setSetExpressCheckoutRequestDetails($ec_details);

   	/*
   	 * Creating CallerServices object
   	 */
	$caller = PayPal::getCallerServices($profile);
	$caller->USE_ARRAYKEY_AS_TAGNAME = true;
	$caller->SUPRESS_OUTTAG_FOR_ARRAY = true;
	$caller->OUTTAG_SUPRESS_ELEMENTS = array('PaymentDetailsItem','FlatRateShippingOptions');
   // Execute SOAP request
   $response = $caller->SetExpressCheckout($ec_request);

   $ack = $response->getAck();

   switch($ack) {
      case 'Success':
      case 'SuccessWithWarning':
         // Good to break out;
         
         // Redirect to paypal.com here
         $token = $response->getToken();
		 $enviroment = ($paymentHandler->getSettingValue('sandboxmode')->value == 1 ? 'sandbox.' : '');
         $payPalURL = 'https://www.' . $enviroment . 'paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$token;
         header("Location: ".$payPalURL);
         exit;

      default:      	
         $_SESSION['error_shop'] = $response;
         print_r($response);exit;
         erLhcoreClassModule::redirect('/shop/error/paypal_handler');
         exit;
   }      
}

$tpl = erLhcoreClassTemplate::getInstance( 'lhshop/handlers/paypal_handler/process.tpl.php');
$tpl->set('payment_handler',$paymentHandler);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('url' => erLhcoreClassDesign::baseurl('shop/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('shop/index','Shop')));
