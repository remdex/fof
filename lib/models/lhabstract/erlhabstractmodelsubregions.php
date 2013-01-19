<?

class erLhAbstractModelSubRegions {
        
   public function getState()
   {
       return array(
           'id'         => $this->id,
           'name'       => $this->name,
           'position'   => $this->position,          
           'visible'    => $this->visible,
           'lon'        => $this->lon,
           'lat'        => $this->lat,
           'aurl'       => $this->aurl,
           'distance'   => $this->distance
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

   public function updateThis()
   {
       $this->aurl = erLhcoreClassCharTransform::TransformToURL($this->name); 
       CSCacheAPC::getMem()->increaseCacheVersion('subregions_cache_version');      
       erLhcoreClassAbstract::getSession()->update($this); 
   }

   public function saveThis()
   {
       $this->aurl = erLhcoreClassCharTransform::TransformToURL($this->name);  
       CSCacheAPC::getMem()->increaseCacheVersion('subregions_cache_version');       
       erLhcoreClassAbstract::getSession()->save($this); 
   }
   
   public function getFields()
   {
       return array('name' => array(
       'type' => 'text',
       'trans' => 'Name',
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
        )),
        
       'lat' => array(
       'type' => 'text',
       'trans' => 'Latitude',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )), 
        
       'lon' => array(
       'type' => 'text',
       'trans' => 'Longitude',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'distance' => array(
       'type' => 'text',
       'trans' => 'Default distance',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'string'
        )), 
       'visible' => array(
       'type' => 'checkbox',
       'trans' => 'Visible',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ))
       
       );
   }
   
   public function __get($var)
   {
       switch ($var) {
       	case 'make':
       	       $this->make = '';
       	       if ($this->region_id > 0) {
       	            $this->make = erLhAbstractModelRegions::fetch($this->region_id);
       	       }
       		   return $this->make;
       		break;
       		
       	case 'location':
       	        return $this->name;
       	    break;	
       	    	
       case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       			
       	default:
       		break;
       }
   }
 
   public function getModuleTranslations()
   {
       return array('name' => 'Sub regions');
   }
   
   public static function getCount($params = array())
   {
       if (!isset($params['disable_sql_cache']))
       {
          $sql = CSCacheAPC::multi_implode(',',$params);
          
          $cache = CSCacheAPC::getMem();          
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('global_cache_subregions_count_'.$cache->getCacheVersion('subregions_cache_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {
              return $result;
          }       
       } 
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_sub_regions" );   
         
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
           }
           
           $q->where( 
                 $conditions   
           );
      }  
             
      $stmt = $q->prepare();       
      $stmt->execute();  
      $result = $stmt->fetchColumn(); 
      
      if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$result);           
      }
          
      return $result; 
   }
   
   public static function fetch($id)
   {   
       
   		if (isset($GLOBALS['erLhAbstractModelSubRegions_'.$id])) return $GLOBALS['erLhAbstractModelSubRegions_'.$id]; 
   		
   		try {
        	$GLOBALS['erLhAbstractModelSubRegions_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelSubRegions', (int)$id );
       	} catch (Exception $e){
       		$GLOBALS['erLhAbstractModelSubRegions_'.$id] = new erLhAbstractModelTruckModel(); 
       	}
        
       	return $GLOBALS['erLhAbstractModelSubRegions_'.$id]; 
       	   	
   }
   
   public function removeThis()
   {
       CSCacheAPC::getMem()->increaseCacheVersion('subregions_cache_version'); 
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
          $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('global_cache_subregions_list_'.$cache->getCacheVersion('subregions_cache_version').$sql);
          
          if (($result = $cache->restore($cacheKey)) !== false)
          {
              return $result;
          }       
       }

       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelSubRegions' );  
            
       $conditions = array(); 
       if (!isset($paramsSearch['smart_select'])) {
             
                  if (isset($params['filter']) && count($params['filter']) > 0)
                  {                     
                       foreach ($params['filter'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue)  );
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
                           $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue)  );
                       } 
                  }
                  
                  if (isset($params['filtergt']) && count($params['filtergt']) > 0)
                  {
                       foreach ($params['filtergt'] as $field => $fieldValue)
                       {
                           $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue)  );
                       } 
                  }      
                  
                  if (count($conditions) > 0)
                  {
                      $q->where( 
                                 $conditions   
                      );
                  } 
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'region_id ASC, position ASC, name ASC' ); 

                  
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_abstract_truck_model' );
           
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
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'region_id ASC, position ASC, name ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_sub_regions.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );

       if (!isset($params['disable_sql_cache'])) {
              $cache->store($cacheKey,$objects);           
       }

      return $objects; 
   }
   
   public $id = null;
   public $name = '';
   public $position = 0;
   public $visible = 1;
   public $lat = 0;
   public $lon = 0;
   public $aurl = '';
   public $distance = 0;

}



?>