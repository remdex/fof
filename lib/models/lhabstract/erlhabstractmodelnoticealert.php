<?

class erLhAbstractModelNoticeAlert {
        
   public function getState()
   {
       return array(
           'id'         		=> $this->id,
           'user_id'      		=> $this->user_id,     
           'days_limit'         => $this->days_limit,
           'last_processed'     => $this->last_processed,
           'last_processed_ymd' => $this->last_processed_ymd,
           'newspaper_group_id' => $this->newspaper_group_id,
           'newspaper_id'      	=> $this->newspaper_id,
           'council_id'      	=> $this->council_id,
           'type_alert'  		=> $this->type_alert,
           'email'  			=> $this->email,
           'comments_size'  	=> $this->comments_size,
           'comment_old'  		=> $this->comment_old
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
       return $this->name;
   }
      
   public function __get($var)
   {
       switch ($var) {
       	
       	case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       		
       	case 'newspapers_groups_id':
       			if ($this->user_id > 0) {
       				try {
       					$user = erLhcoreClassModelUser::fetch($this->user_id);
       					$filter = erLhcoreClassUser::getAdminFilter(array( 'user_data' => $user ));
       					
       					return $filter;
       				} catch (Exception $e) {
       					
       				}
       			}
       			
       			return array();
       		break;	
       	
       	default:
       		break;
       }
   }
     
      
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_notice_alert" );   
         
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
      
      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
           }
      } 

      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
           }
      }
       
      if (isset($params['filterlike']) && count($params['filterlike']) > 0)
      {
           foreach ($params['filterlike'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->like( $field, $q->bindValue('%'.$fieldValue.'%') );
           }
      }

      if (isset($params['leftjoin']) && count($params['leftjoin']) > 0)
      {
           foreach ($params['leftjoin'] as $table => $joinOn)
           {
               $q->leftJoin( $table, $q->expr->eq( $joinOn[0], $joinOn[1] ) );
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
   
   public static function fetch($id)
   {   
       if (isset($GLOBALS['erLhAbstractModelNoticeAlert_'.$id])) return $GLOBALS['erLhAbstractModelNoticeAlert_'.$id];         
       $GLOBALS['erLhAbstractModelNoticeAlert_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelNoticeAlert', (int)$id );
       
       return $GLOBALS['erLhAbstractModelNoticeAlert_'.$id];
   }
   
   public function removeThis()
   {
       erLhcoreClassAbstract::getSession()->delete($this);
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 500, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelNoticeAlert' );  
       
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
      
      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
           }
      } 

      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
           }
      }

      if (isset($params['filterlike']) && count($params['filterlike']) > 0)
      {
           foreach ($params['filterlike'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->like( $field, $q->bindValue('%'.$fieldValue.'%') );
           }
      } 
                  
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
       
      $q->limit($params['limit'],$params['offset']);
      $q->orderBy('id DESC');
              
       $objects = $session->find( $q, 'erLhAbstractModelNoticeAlert' );
         
      return $objects; 
   }
   
   const ALERT_TYPE_PDF_OVERDUE = 0;
   
   public $id = null;
   public $user_id = 0;
   public $days_limit = 0;
   public $last_processed = 0;
   public $newspaper_group_id = 0;
   public $newspaper_id = 0;
   public $council_id = 0;
   public $last_processed_ymd = 0;
   public $email = '';
   public $comments_size = 0;
   public $comment_old = 0;
     
   public $type_alert = self::ALERT_TYPE_PDF_OVERDUE;
   
      
}

?>