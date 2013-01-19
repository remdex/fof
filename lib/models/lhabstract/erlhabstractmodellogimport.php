<?

class erLhAbstractModelLogImport {
        
   public function getState()
   {
       return array(
           'id'         => $this->id,
           'atime'      => $this->atime,
           'ttime'      => $this->ttime,
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
            		
       case 'date':
       		   return date('Y-m-d H:i:s',$this->atime);       		   
       		break;
       		
       	default:
       		break;
       }
   }
    
   public static function addLogMessage($message, $time) {
       $log = new erLhAbstractModelLogImport();
       $log->atime = time();
       $log->message = $message;
       $log->ttime = $time;
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
       return array('name' => 'Log import');
   }
   
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_log_import" );   
         
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
       if (isset($GLOBALS['CacheGlobalModelLogImport_'.$id])) return $GLOBALS['CacheGlobalModelLogImport_'.$id];         
       $GLOBALS['CacheGlobalModelLogImport_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelLogImport', (int)$id );
       
       return $GLOBALS['CacheGlobalModelLogImport_'.$id];
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
       $q = $session->createFindQuery( 'erLhAbstractModelLogImport' );  
       
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
              
       $objects = $session->find( $q, 'erLhAbstractModelLogImport' );
         
      return $objects; 
   }
   
   public $id = null;
   public $atime = '';
   public $ttime = '';
   public $message = '';
   
}

?>