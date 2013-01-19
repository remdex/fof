<?

class erLhAbstractModelSortOption {
        
   public function getState()
   {
       $stateArray = array (
           'id'         => $this->id,
           'position'   => $this->position,
           'value'      => $this->value,
           'identifier' => $this->identifier,
           'dir'        => $this->dir,       		
           'sort_type'  => $this->sort_type
       ); 
              
       foreach (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'available_locales' ) as $locale) {
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
       return array(
       'name' => array(
       'type' => 'text',
       'trans' => 'Name',
       'multilanguage' => true,
       'frontend' => 'name',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'position' => array(
       'type' => 'text',
       'trans' => 'Position',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'value' => array(
       'type' => 'text',
       'trans' => 'Sort value',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'identifier' => array(
       'type' => 'text',
       'trans' => 'Identifier',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'dir' => array(
       'type' => 'text',
       'trans' => 'Direction, 0 - ASC, 1 - DESC',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'sort_type' => array(
       'type' => 'text',
       'trans' => 'Sort type',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ))
        );
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'Sort options');
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_sort_option" );   
         
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
   
   public static function fetch($id)
   {   
       if (isset($GLOBALS['erLhAbstractModelSortOption_'.$id])) return $GLOBALS['erLhAbstractModelSortOption_'.$id];         
       $GLOBALS['erLhAbstractModelSortOption_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelSortOption', (int)$id );
       
       return $GLOBALS['erLhAbstractModelSortOption_'.$id];
   }
   
   public function removeThis()
   {
       erLhcoreClassAbstract::getSession()->delete($this);
   }
   
   public static function getList($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 500, 'offset' => 0);       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       if (!isset($params['disable_sql_cache']))
       {
          $sql = CSCacheAPC::multi_implode(',',$params);
          
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('global_cache_'.$cache->getCacheVersion('site_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {
              return $result;
          }       
       }
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelSortOption' );  
       
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
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC' ); 
      
      $objects = $session->find( $q );
       
      if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$objects);           
      } 
              
      
         
      return $objects; 
   }
   
   public $id = null;
   public $position = 0;
   public $value = 0;
   public $dir = 0; // 0 - ASC, 1 - DESC
   public $sort_type = ''; // 0 - ASC, 1 - DESC
   

}



?>