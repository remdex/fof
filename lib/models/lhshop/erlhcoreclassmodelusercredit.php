<?php

class erLhcoreClassModelShopUserCredit {
       

   public function getState()
   {
       return array(
               'user_id'     => $this->user_id,  
               'credits'     => $this->credits
       );
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function fetch($user_id)
   {
   		try {
       		$UserCredits = erLhcoreClassShop::getSession('slave')->load( 'erLhcoreClassModelShopUserCredit', (int)$user_id );
   		} catch (Exception $e){
   			$UserCredits = new erLhcoreClassModelShopUserCredit();
   			$UserCredits->user_id = $user_id;
   			erLhcoreClassShop::getSession()->save($UserCredits);
   		}
   		
       return $UserCredits;
   }
   
   public function __toString()
   {
   		return $this->value;
   }   
      
   public static function getListCount($params = array())
   {
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(user_id)" )->from( "lh_shop_user_credit" );     
         
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
       $q = $session->createFindQuery( 'erLhcoreClassModelShopUserCredit' );  
       
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
                           $conditions[] = $q->expr->lt( $field, $fieldValue );
                       } 
                  }
                  
                  if (isset($params['filtergt']) && count($params['filtergt']) > 0)
                  {
                       foreach ($params['filtergt'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->gt( $field, $fieldValue );
                       } 
                  }      
                  
                  if (count($conditions) > 0)
                  {
                      $q->where( 
                                 $conditions   
                      );
                  } 
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'user_id DESC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_shop_user_credit' );
           
           if (isset($params['filter']) && count($params['filter']) > 0)
          {                     
               foreach ($params['filter'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->eq( $field, $fieldValue );
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
                   $conditions[] = $q2->expr->lt( $field, $fieldValue );
               } 
          }
          
          if (isset($params['filtergt']) && count($params['filtergt']) > 0)
          {
               foreach ($params['filtergt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->gt( $field, $fieldValue );
               } 
          }      
          
          if (count($conditions) > 0)
          {
              $q2->where( 
                         $conditions   
              );
          }
           
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'user_id DESC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_shop_user_credit.user_id', 'items.user_id' );          
       }
              
      $objects = $session->find( $q ); 
         
      return $objects; 
   }

   public $user_id = 0;  
   public $credits =0;   
}

?>