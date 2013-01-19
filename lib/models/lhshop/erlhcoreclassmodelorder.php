<?

class erLhcoreClassModelShopOrder {
        
   public function getState()
   {
       return array(
               'id'     	=> $this->id,
               'order_time' => $this->order_time,             
               'user_id'	=> $this->user_id,             
               'status'     => $this->status,          
               'email'      => $this->email,          
               'payment_gateway'      => $this->payment_gateway,          
               'basket_id'  => $this->basket_id ,         
               'currency'  => $this->currency ,         
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($id)
   {
       $imageVariation = erLhcoreClassShop::getSession('slave')->load( 'erLhcoreClassModelShopOrder', (int)$id );     
       return $imageVariation;
   } 
   
   public function userOrderFetch($order_id,$skipChecking = false)
   {
   		$order = erLhcoreClassModelShopOrder::fetch($order_id);
       
        if ($skipChecking==true) return $order;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($order->user_id == $currentUser->getUserID()) return $order;
        
       return false; 
   }
   
   public function __get($variable)
   {
   		switch ($variable) {
   			case 'amount':
   				$this->amount = 0;   				
   				foreach ($this->order_items as $item)
   				{
   					$this->amount += $item->price;
   				}		   			
   				return $this->amount;   				
   			break;
   			
   			case 'amount_credits':
   				$this->amount_credits = 0;   				
   				foreach ($this->order_items as $item)
   				{
   					$this->amount_credits += $item->credits;
   				}		   			
   				return $this->amount_credits; 
   			break;
   				
   			case 'order_items':   					
   					return erLhcoreClassModelShopOrderItem::getList(array('filter' => array('order_id' => $this->id)));
   				break;
   			default:
   				break;
   		}
   }
   
   //Copy basket items to order_items
   public function initOrderItems()
   {
   		// Delete old basket items
   		$session = erLhcoreClassShop::getSession();
        $q = $session->database->createDeleteQuery();  
        $q->deleteFrom( 'lh_shop_order_item' )->where(  $q->expr->eq(  'order_id', $q->bindValue(  $this->id ) ) );  
        $stmt = $q->prepare();
		$stmt->execute();
		
		$basket = erLhcoreClassModelShopBasketSession::getInstance();
		$creditPrice = erLhcoreClassModelShopBaseSetting::fetch('credit_price');
		
		$currentUser = erLhcoreClassUser::instance();
		
		$this->user_id = $currentUser->getUserID();
		$this->order_time = time();
		
		foreach ($basket->basket_items as $item)
		{
			$orderItem = new erLhcoreClassModelShopOrderItem();
			$orderItem->image_variation_id = $item->variation_id;
			$orderItem->pid = $item->pid;
			$orderItem->order_id = $this->id;
			
			// Store original prices
			$orderItem->credits = $orderItem->image_variation->credits;
			$orderItem->credit_price = $creditPrice->value;
						
			$session->save($orderItem);
		}
   }
   
   public function generateDownloadHashLink()
   {
   		foreach ($this->order_items as $item) {
   			$item->hash = erLhcoreClassModelForgotPassword::randomPassword(40);
   			erLhcoreClassShop::getSession()->update($item);
   		}
   }
   
   public function decreaseCredits()
   {
   		$currentUser = erLhcoreClassUser::instance();
   		$credits = erLhcoreClassModelShopUserCredit::fetch($currentUser->getUserID());
   		$credits->credits -= $this->amount_credits;
   		erLhcoreClassShop::getSession()->update($credits);
   }
   
   public function removeThis()
   {
   		foreach ($this->order_items as $orderItem)
   		{
   			$orderItem->removeThis();
   		}
       erLhcoreClassShop::getSession()->delete($this);  
   }
        
   public static function getListCount($params = array())
   {
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_shop_order" );   
         
       if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
       } 
           
       if (isset($params['filter']) && count($params['filter']) > 0)
       {                     
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }           
      } 
      
      if (count($conditions) > 0)
      {
	      $q->where( 
	                 $conditions   
	           );
      }  
            
      $stmt = $q->prepare();       
      $stmt->execute();  
      $result = $stmt->fetchColumn(); 
            
      return $result; 
   }
      
   public static function getList($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 30, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelShopOrder' ); 
        
       $conditions = array();
          
       if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
       } 
           
       if (isset($params['filter']) && count($params['filter']) > 0)
       {                     
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }           
      } 
      
      if (count($conditions) > 0)
      {
	      $q->where( 
	                 $conditions   
	           );
      }
      
      $q->limit($params['limit'],$params['offset']);
          
      $q->orderBy( isset($paramsSearch['sort']) ? $paramsSearch['sort'] : 'id DESC' ); 
              
      $objects = $session->find( $q );         
      return $objects; 
   }
   
   public static function getInstance($basket_id)  
   {
        if ( is_null( self::$instance ) )
        {          
        	$list = erLhcoreClassModelShopOrder::getList(array('filter' => array('basket_id' => $basket_id,'status' => erLhcoreClassModelShopOrder::ORDER_STATUS_DRAFT )));
        	if (count($list) == 0)
        	{	        	
				    self::$instance = new erLhcoreClassModelShopOrder();
				    self::$instance->basket_id = $basket_id;			    
				    self::$instance->status = erLhcoreClassModelShopOrder::ORDER_STATUS_DRAFT;	
				    self::$instance->currency = erLhcoreClassModelShopBaseSetting::fetch('main_currency')->value;
				    		    
				    erLhcoreClassShop::getSession()->save(self::$instance); 	
			} else {
				self::$instance = array_shift($list);
			}
        }
        
        return self::$instance;
   }
     
   public $id = null;
   public $order_time = 0;
   public $user_id = 0;
   public $status = 0;
   public $basket_id = 0;
   public $email = '';
   public $payment_gateway = '';
   public $currency = '';
    
   const ORDER_STATUS_DRAFT = 0;
   const ORDER_STATUS_CONFIRMED = 1;
   const ORDER_STATUS_PAYED = 2;
   
   private static $instance = null;
}

?>