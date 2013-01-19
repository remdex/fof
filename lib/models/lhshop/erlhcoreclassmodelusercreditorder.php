<?php

class erLhcoreClassModelShopUserCreditOrder {

   public function getState()
   {
       return array(
               'id'     => $this->id,  
               'user_id'     => $this->user_id,  
               'credits'     => $this->credits,
               'status'     => $this->status,
               'date'     => $this->date,
               'payment_gateway'     => $this->payment_gateway,
               'currency'     => $this->currency,
       );
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($order_id)
   {   	
       $UserCredits = erLhcoreClassShop::getSession('slave')->load( 'erLhcoreClassModelShopUserCreditOrder', (int)$order_id );
   		   		
       return $UserCredits;
   }
   
   public function __toString()
   {
   		return $this->value;
   }   

   public function __get($variable)
   {
   		switch ($variable) {
   			case 'user':
	   				$this->user = erLhcoreClassModelUser::fetch($this->user_id);
	   				return $this->user;
   				break;
   				
   			case 'amount':
   					$creditPrice = erLhcoreClassModelShopBaseSetting::fetch('credit_price');
	   				$this->amount = $this->credits * $creditPrice->value;
	   				return $this->amount;
   				break;
   		
   			default:
   				break;
   		}
   }
     
   public static function getListCount($params = array())
   {
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(user_id)" )->from( "lh_shop_user_credit_order" );     
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           } 
      }  
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field, $fieldValue );
           } 
      }     
       
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue) );
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
      
   public function removeThis() {
   	               
   		erLhcoreClassShop::getSession()->delete($this);   		   		
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelShopUserCreditOrder' );  
       
       $conditions = array(); 
       if (!isset($paramsSearch['smart_select'])) {
             
                  if (isset($params['filter']) && count($params['filter']) > 0)
                  {                     
                       foreach ($params['filter'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
                       }
                  } 
                  
                  if (isset($params['filterin']) && count($params['filterin']) > 0)
                  {
                       foreach ($params['filterin'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->in( $field, $fieldValue );
                       } 
                  }
                  
                  if (isset($params['filterlt']) && count($params['filterlt']) > 0)
                  {
                       foreach ($params['filterlt'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue) );
                       } 
                  }
                  
                  if (isset($params['filtergt']) && count($params['filtergt']) > 0)
                  {
                       foreach ($params['filtergt'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue) );
                       } 
                  }      
                  
                  if (count($conditions) > 0)
                  {
                      $q->where( 
                                 $conditions   
                      );
                  } 
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_shop_user_credit_order' );
           
           if (isset($params['filter']) && count($params['filter']) > 0)
          {                     
               foreach ($params['filter'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->eq( $field, $q->bindValue($fieldValue) );
               }
          } 
          
          if (isset($params['filterin']) && count($params['filterin']) > 0)
          {
               foreach ($params['filterin'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->in( $field, $fieldValue );
               } 
          }
          
          if (isset($params['filterlt']) && count($params['filterlt']) > 0)
          {
               foreach ($params['filterlt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->lt( $field, $q->bindValue($fieldValue) );
               } 
          }
          
          if (isset($params['filtergt']) && count($params['filtergt']) > 0)
          {
               foreach ($params['filtergt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->gt( $field, $q->bindValue($fieldValue) );
               } 
          }      
          
          if (count($conditions) > 0)
          {
              $q2->where( 
                         $conditions   
              );
          }
           
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_shop_user_credit_order.id', 'items.id' );          
       }
              
      $objects = $session->find( $q ); 
         
      return $objects; 
   }
   
   public static function getInstance()  
   {
   		$currentUser = erLhcoreClassUser::instance();
        if ( is_null( self::$instance ) )
        {          
        	$list = erLhcoreClassModelShopUserCreditOrder::getList(array('filter' => array('user_id' => $currentUser->getUserID(),'status' => erLhcoreClassModelShopUserCreditOrder::ORDER_STATUS_DRAFT )));
        	if (count($list) == 0)
        	{	        	
				    self::$instance = new erLhcoreClassModelShopUserCreditOrder();
				    self::$instance->user_id = $currentUser->getUserID();			    
				    self::$instance->status = erLhcoreClassModelShopUserCreditOrder::ORDER_STATUS_DRAFT;			    
				    self::$instance->currency = erLhcoreClassModelShopBaseSetting::fetch('main_currency')->value;			    
				    erLhcoreClassShop::getSession()->save(self::$instance); 	
			} else {
				self::$instance = array_shift($list);
			}
        }
        
        return self::$instance;
   }
   
   const ORDER_STATUS_DRAFT = 0;
   const ORDER_STATUS_CONFIRMED = 1;
   const ORDER_STATUS_PAYED = 2;
   
   private static $instance = null;
   
   public $id = null;  
   public $user_id =0;   
   public $credits =0;   
   public $status =0;   
   public $date =0;   
   public $payment_gateway ='';   
   public $currency ='';   
}

?>