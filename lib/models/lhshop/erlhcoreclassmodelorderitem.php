<?php

class erLhcoreClassModelShopOrderItem {
       

   public function getState()
   {
       return array(
               'id'             	=> $this->id,
               'order_id'     		=> $this->order_id,  
               'pid'        		=> $this->pid,
               'image_variation_id' => $this->image_variation_id,
               'hash'   			=> $this->hash,
               'credit_price'   	=> $this->credit_price,
               'credits'   			=> $this->credits,
               'download_count'   			=> $this->download_count,
       );
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public function __toString()
   {
   		return $this->value;
   }   
   
   public function __get($variation)
   {
   		switch ($variation) {
   			case 'image_variation':
   					$this->image_variation = erLhcoreClassModelShopImageVariation::fetch($this->image_variation_id);
   					return $this->image_variation;
   				break;
   				
   			case 'price':
   					$creditPrice = erLhcoreClassModelShopBaseSetting::fetch('credit_price');   					
   					$this->price = $creditPrice->value*$this->image_variation->credits;
   					return $this->price;
   				break;
   				
   			case 'credits':   
   					$this->credits = $this->image_variation->credits;   						
   					return $this->credits;
   				break;
   						
   			case 'image':
   					$this->image = erLhcoreClassModelGalleryImage::fetch($this->pid);
   					return $this->image;
   				break;
   							
   			case 'download_left':
   					$maxDownloads = erLhcoreClassModelShopBaseSetting::fetch('max_downloads');
   					$this->download_left = $maxDownloads->value - $this->download_count;
   					return $this->download_left;
   				break;
   		
   			default:
   				break;
   		}
   }
   
   public static function getListCount($params = array())
   {
       $session = erLhcoreClassShop::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_shop_order_item" );     
         
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
       $q = $session->createFindQuery( 'erLhcoreClassModelShopOrderItem' );  
       
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
           $q2->select( 'pid' )->from( 'lh_shop_order_item' );
           
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
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_shop_order_item.id', 'items.id' );          
       }
       
       
      $objects = $session->find( $q ); 
         
      return $objects; 
   }
        
   public $id = null;
   public $order_id = '';  
   public $pid = '';
   public $image_variation_id = '';
   public $hash = '';
   public $credit_price = 0;
   public $credits = 0;
   public $download_count = 0;

}


?>