<?

class erLhAbstractModelLog {
        
   public function getState()
   {
       return array(
           'id'         => $this->id,
           'atime'      => $this->atime,
           'user_id'    => $this->user_id,
           'message'    => $this->message
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
       	       $this->left_menu = 'users_management';
       		   return $this->left_menu;
       		break;
       		
       case 'user':
       		   return erLhcoreClassModelUser::fetch($this->user_id)->username;       		   
       		break;	
       		
       case 'date':
       		   return date('Y-m-d H:i:s',$this->atime);       		   
       		break;
       		
       	default:
       		break;
       }
   }
    
   public static function addLogMessage($message) {
       $currentUser = erLhcoreClassUser::instance();
       $log = new erLhAbstractModelLog();
       $log->atime = time();
       $log->message = $message;
       $log->user_id = $currentUser->getUserID();
       if (!is_numeric($log->user_id)){
           $log->user_id = 1;
       }
       $log->saveThis();
   }
   
   public function getFields()
   {
       return array('message' => array(
       'type' => 'text',
       'trans' => 'Name',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        'user' => array(
       'type' => 'text',
       'trans' => 'User performed action',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        'date' => array(
       'type' => 'text',
       'trans' => 'Date action',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ))        
       );
   }
   
      
   public function getModuleTranslations()
   {
       return array('name' => 'Log');
   }
   
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_log" );   
         
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
       if (isset($GLOBALS['CacheGlobalModelLog_'.$id])) return $GLOBALS['CacheGlobalModelLog_'.$id];         
       $GLOBALS['CacheGlobalModelDoor_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelLog', (int)$id );
       
       return $GLOBALS['CacheGlobalModelLog_'.$id];
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
       $q = $session->createFindQuery( 'erLhAbstractModelLog' );  
       
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
       
      $q->limit($params['limit'],$params['offset']);
      $q->orderBy('id DESC');
              
       $objects = $session->find( $q, 'erLhAbstractModelLog' );
         
      return $objects; 
   }
   
   public $id = null;
   public $atime = '';
   public $user_id = 0;
   public $message = '';
   
}

?>