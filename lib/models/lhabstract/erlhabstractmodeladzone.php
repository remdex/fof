<?

class erLhAbstractModelAdZone {
  
   public function getState()
   {
       return array(
           'id'         	=> $this->id,
           'name'       	=> $this->name,
           'content'    	=> $this->content,
           'header_slot'    => $this->header_slot
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

   public function saveThis() {
   	   		
   		erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   		
   		// Clean cache because templates get's compiled
	   	$CacheManager = erConfigClassLhCacheConfig::getInstance();
	   	$CacheManager->expireCache();
   }
   
   public function updateThis() {
   	
   		// Clean cache because templates get's compiled
   		erLhcoreClassAbstract::getSession()->saveOrUpdate( $this );
   		
   		// Clean cache because templates get's compiled
   		$CacheManager = erConfigClassLhCacheConfig::getInstance();
   		$CacheManager->expireCache();
   }
   
   
   public function getFields()
   {
       return array(
       'name' => array(
           'type' => 'text',
           'trans' => 'Name',
           'required' => true,       
           'validation_definition' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            )        
       ),
       'content' => array (          
           'type' => 'textarea',
           'trans' => 'Content',
       	   'height' => '150px',
           'required' => true,   
       	   'hidden' => true,    
           'validation_definition' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            )        
       ),
       'header_slot' => array (          
           'type' => 'textarea',
           'trans' => 'Header slot definition',
           'required' => true, 
       	   'height' => '100px',  
       	   'hidden' => true,    
           'validation_definition' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
            )
       )
       
       /*,
       'newspaper_group' => array (
       				'type' => 'combobox_multi',
       				'frontend' => 'body',
       				'backend' => 'newspaper_group_id',
       				'source' => 'erLhAbstractModelNewspaperGroup::getList',
       				'params_call' => array('limit' => 5000, 'offset' => 0),
       				'trans' => 'Newspaper groups allowed<br/>to have their own banner codes.',
       				'hidden' => true,
       				'backend_call' => 'assignNewspaperGroupToMake',
       				'required' => true,
       				'validation_definition' => new ezcInputFormDefinitionElement(
       						ezcInputFormDefinitionElement::OPTIONAL, 'int',
       						null,
       						FILTER_REQUIRE_ARRAY
       )),*/
       		
       );
   }
   
   public function assignNewspaperGroupToMake($shipperArray)
   {
   	if ($this->id > 0) {
   		 
   		$session = erLhcoreClassAbstract::getSession();
   		$q = $session->database->createDeleteQuery();
   		$q->deleteFrom( 'lh_abstract_ad_zone_newspaper_group' )
   		->where( $q->expr->eq( 'ad_id', $q->bindValue( $this->id ) ) );
   		$stmt = $q->prepare();
   		$stmt->execute();
   		 
   		foreach ($shipperArray as $shipperID){
   			$q = $session->database->createInsertQuery();
   			$q->insertInto( 'lh_abstract_ad_zone_newspaper_group' )
   			->set( 'ad_id', $q->bindValue($this->id) )
   			->set( 'newspaper_group_id', $q->bindValue( $shipperID ) );
   			$stmt = $q->prepare();
   			$stmt->execute();
   		}
   		 
   	} else {
   		$this->newspaper_group_id = $shipperArray;
   	}
   }
   
   public function getNewspaperGroupAssigned($fetchObjects = false)
   {
	   	if ($this->id > 0) {
	   		$session = erLhcoreClassAbstract::getSession();
	   		$q = $session->database->createSelectQuery();
	   		$q->select( "newspaper_group_id" )->from( "lh_abstract_ad_zone_newspaper_group" );
	   		$q->where(
	   				$q->expr->eq( 'ad_id', $this->id )
	   		);
	   		$stmt = $q->prepare();
	   		$stmt->execute();
	   		$result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
	   		 
	   		if ($fetchObjects == true && $result !== false && count($result) > 0){
	   			//return erLhAbstractModelBodyStyle::getList(array('filterin' => array('id' => $result)));
	   		}
	   		 
	   		return $result;
	   	}
	   	 
	   	return array();
   }

   
   public function __get($var)
   {
   	
   	switch ($var) {
   		case 'left_menu':
   			$this->left_menu = '';
   			return $this->left_menu;
   			break;
   			 
   		case 'newspaper_group_id':
   			$this->newspaper_group_id = $this->getNewspaperGroupAssigned();
   			return $this->newspaper_group_id;
   			break;   	
   	
   		default:
   			break;
   	}
      		
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'Ad zones');
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_ad_zone" );   
         
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
       if (isset($GLOBALS['erLhAbstractModelAdZone_'.$id])) return $GLOBALS['erLhAbstractModelAdZone_'.$id];
       $GLOBALS['erLhAbstractModelAdZone_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelAdZone', (int)$id );
       
       return $GLOBALS['erLhAbstractModelAdZone_'.$id];
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
       $q = $session->createFindQuery( 'erLhAbstractModelAdZone' );  
            
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
      
      $q->limit($params['limit'],$params['offset']);
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'id ASC' ); 
      
              
       $objects = $session->find( $q );
         
      return $objects; 
   }

   public $id = null;
   public $name = '';
   public $content = '';
   public $header_slot = '';
}

?>