<?php

class erLhcoreClassShopPaymentHandlerCredits{
      
   function __construct()
   {
 
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