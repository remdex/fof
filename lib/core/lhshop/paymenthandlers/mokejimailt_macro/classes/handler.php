<?php

class erLhcoreClassShopPaymentHandlerMokejimaiMakro{
      
   function __construct()
   {
 
   }
   
   public function __get($variable)
   {
   		switch ($variable) {
   			case 'sign':
   			   $arrFields = array(
		       'projectid' => $this->handler->getSettingValue('projectid'),
		       'orderid' => $this->basket->order->id,
		       'lang' => $this->handler->getSettingValue('lang'),
		       'amount' =>round($this->basket->order->amount*100),
		       'currency' => $this->handler->getSettingValue('currency'),
		       'accepturl' => 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('shop/accept').'/'.$this->handler->identifier,
		       'cancelurl' => 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('shop/cancel').'/'.$this->handler->identifier,
		       'callbackurl' => 'http://'.$_SERVER['HTTP_HOST'].erLhcoreClassDesign::baseurl('shop/callback').'/'.$this->handler->identifier,
		       'country' => $this->handler->getSettingValue('country'),
		       'logo' => '',
		       'p_firstname' => '',
		       'p_lastname' => '',
		       'p_email' => $this->basket->order->email,
		       'p_street' => '',
		       'p_city' => '',
		       'p_state' => '',
		       'p_zip' => '',
		       'p_countrycode' => '',
		       'test' => $this->handler->getSettingValue('test')
		       );
		   
		       # -- Do sign --
		       $data = '';
		       foreach ($arrFields as $num => $value) if (trim($value) != '') $data .= sprintf("%03d", strlen($value)) . strtolower($value);
		       $sign = md5($data . $this->handler->getSettingValue('sign_password'));       
		       $this->sign = $sign;
		       return $this->sign;
   			   break;
   		
   			default:
   				break;
   		}
   }
   
   public function setBasket($basket)
   {
   		$this->basket = $basket;
   }
   
   public function setHandler($handler)
   {
   		$this->handler = $handler;
   }
    
   var $basket = null;
   var $handler = null;
}


?>