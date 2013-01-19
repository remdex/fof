<?php

class erLhcoreClassShopPaymentHandler{

	function __construct($moduleInfo)
	{
		foreach ($moduleInfo as $param => $value)
		{
			$this->{$param} = $value;
		}
	}

	public static function getHandlers()
	{
		$files = ezcBaseFile::findRecursive(
		erLhcoreClassSystem::instance()->SiteDir . 'lib/core/lhshop/paymenthandlers',
		array( '@/setup.php$@' )
		);

		$handlers = array();

		foreach ($files as $file)
		{
			require_once($file);
			$handlers[] = new erLhcoreClassShopPaymentHandler($definition);

		}

		return $handlers;
	}

	public function __get($variable)
	{
		switch ($variable) {
			case 'active':										
					$this->active = erLhcoreClassModelShopPaymentSetting::getListCount(array('filter' => array('identifier' => $this->identifier,'param' => 'active', 'value' => 1))) == 1;
					return $this->active;
				break;
				
			case 'current_settings':
					$this->current_settings = array();					
					foreach (erLhcoreClassModelShopPaymentSetting::getList(array('filter' => array('identifier' => $this->identifier))) as $param)
					{
						$this->current_settings[$param->param] = $param;
					}					
					return $this->current_settings;
				break;
			case 'basket': {
				$this->basket = erLhcoreClassModelShopBasketSession::getInstance();
				return $this->basket;
			} break;
			
			case 'handler':{
				require_once(erLhcoreClassSystem::instance()->SiteDir . 'lib/core/lhshop/paymenthandlers/'.$this->identifier.'/classes/handler.php');
				$this->handler = new $this->handler_classname;
				$this->handler->setBasket($this->basket);
				$this->handler->setHandler($this);
				return $this->handler;
			} break;
			
			default:
				break;
		}
	}
	
	public static function fetch($identifier){
		
		if (file_exists(erLhcoreClassSystem::instance()->SiteDir . 'lib/core/lhshop/paymenthandlers/'.$identifier.'/setup.php')){
			require(erLhcoreClassSystem::instance()->SiteDir . 'lib/core/lhshop/paymenthandlers/'.$identifier.'/setup.php');
			return new erLhcoreClassShopPaymentHandler($definition);
		}

		return false;	
	}
	
	
	
	public function getSettingValue($param) {
		if (key_exists($param,$this->current_settings)){
			return $this->current_settings[$param];
		}
		return false;
	}
	
	public function setSettingValue($param,$value){
		
		if (($setting = $this->getSettingValue($param)) !== false){
			$setting->value = $value;
			erLhcoreClassShop::getSession()->update($setting); 			
		} else {
			$setting = new erLhcoreClassModelShopPaymentSetting();
			$setting->identifier = $this->identifier;
			$setting->param = $param;
			$setting->value = $value;
			erLhcoreClassShop::getSession()->save($setting);			
		}
		
		$this->current_settings[$param] = $setting;
	}

}


?>