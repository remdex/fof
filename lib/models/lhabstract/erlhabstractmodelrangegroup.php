<?

class erLhAbstractModelRangeGroup {
        
   public function getState()
   {
       $stateArray = array (
           'id'         => $this->id,
           'position'   => $this->position
       );
              
       foreach (erConfigClassLhConfig::getInstance()->conf->getSetting( 'site', 'available_locales' ) as $locale) {
           $stateArray['name_'.strtolower($locale)] = $this->{'name_'.strtolower($locale)};
       }
       
       return $stateArray;
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
     
   public function getFields()
   {
       return array('name' => array(
       'type' => 'text',
       'trans' => 'Name',
       'multilanguage' => true,
       'frontend' => 'name',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )        
       ),'position' => array(
       'type' => 'text',
       'trans' => 'Position',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )        
       ));
   }
   
   public function __get($var)
   {
       switch ($var) {
       	case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       case 'name':        
       	        $value = $this->{'name_'.strtolower(erLhcoreClassSystem::instance()->Language)};
                if ($value != '') return $value;
                return $this->name_en_en;
       	    break;
       	default:
       		break;
       }
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'Range groups');
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_range_group" );   
         
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $fieldValue );
           }
           
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
       if (isset($GLOBALS['erLhAbstractModelRangeGroup_'.$id])) return $GLOBALS['erLhAbstractModelRangeGroup_'.$id];         
       $GLOBALS['erLhAbstractModelRangeGroup_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelRangeGroup', (int)$id );
       
       return $GLOBALS['erLhAbstractModelRangeGroup_'.$id];
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
       $q = $session->createFindQuery( 'erLhAbstractModelRangeGroup' );  
       
       $conditions = array(); 
       if (!isset($paramsSearch['smart_select'])) {
             
                  if (isset($params['filter']) && count($params['filter']) > 0)
                  {                     
                       foreach ($params['filter'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->eq( $field, $fieldValue );
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
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, name_en_en ASC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_abstract_range_group' );
           
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
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, name_en_en ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_range_group.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );
         
      return $objects; 
   }
   
   public $id = null;
   public $position = 0;
   

}



?>