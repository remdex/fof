<?

class erLhAbstractModelCouncil {
        
	public function getState()
	{
		return array (
			'id'         => $this->id,
			'name'       => $this->name,		
			'location'   => $this->location,		
			'lat'        => $this->lat,		
			'lon'        => $this->lon,		
			'aname'      => $this->aname,		
			'telephone'  => $this->telephone,		
			'other'  	 => $this->other,
			'position'   => $this->position,
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
		$this->aname = erLhcoreClassCharTransform::TransformToURL($this->name);
		CSCacheAPC::getMem()->increaseCacheVersion('councils_cache_version');
		erLhcoreClassAbstract::getSession()->update($this);
	}
		
	public function saveThis()
	{
		$this->aname = erLhcoreClassCharTransform::TransformToURL($this->name);
		CSCacheAPC::getMem()->increaseCacheVersion('councils_cache_version');
		erLhcoreClassAbstract::getSession()->save($this);
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
		)),       			
		'telephone' => array(
		'type' => 'text',
		'trans' => 'Telephone',
		'required' => false,       
		'hidden' => true,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),       			
       	'newspaper_group_id' => array (
       					'type' => 'combobox_multi',
       					'frontend' => 'newspaper_group',
       					'backend' => 'newspaper_group_array_id',
       					'source' => 'erLhAbstractModelNewspaperGroup::getList',
       					'params_call' => array('limit' => 5000, 'offset' => 0),
       					'trans' => 'Newspapers group',
       					'hidden' => true,
       					'backend_call' => 'assignNewspaperGroupToCouncil',
       					'required' => false,
       					'validation_definition' => new ezcInputFormDefinitionElement(
       							ezcInputFormDefinitionElement::OPTIONAL, 'int',
       							null,
       							FILTER_REQUIRE_ARRAY
       					)
       			),
       	'location' => array(
	       'type' => 'location',
	       'trans' => 'Location',
	       'required' => true,
	       'validation_definition' => new ezcInputFormDefinitionElement(
	            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
	    )),
       	'position' => array(
       		'type' => 'text',
       		'trans' => 'Position',
       		'required' => true,
       		'hidden' => true,
       		'validation_definition' => new ezcInputFormDefinitionElement(
       			ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
       	)),
       	'other' => array(
       		'type' => 'checkbox',
       		'trans' => 'Other',
       		'required' => false,
       		'hidden' => true,
       		'validation_definition' => new ezcInputFormDefinitionElement(
       			ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
       	)),       			
       	);
	}
	
	public function defaultSort()
	{
		return 'position ASC, name ASC';
	}
	
	public function synchronizeAttribute()
	{
		$this->assignNewspaperGroupToCouncil($this->newspaper_group_array_id);		
	}
		
	public function assignNewspaperGroupToCouncil($shipperArray)
	{
		if ($this->id > 0) {
			 
			$session = erLhcoreClassAbstract::getSession();
			$q = $session->database->createDeleteQuery();
			$q->deleteFrom( 'lh_abstract_newspapergroup_council' )
			->where( $q->expr->eq( 'council_id', $q->bindValue( $this->id ) ) );
			$stmt = $q->prepare();
			$stmt->execute();
			 
			foreach ($shipperArray as $shipperID){
				$q = $session->database->createInsertQuery();
				$q->insertInto( 'lh_abstract_newspapergroup_council' )
				->set( 'council_id', $q->bindValue($this->id) )
				->set( 'newspaper_group_id', $q->bindValue( $shipperID ) );
				$stmt = $q->prepare();
				$stmt->execute();
			}
			 
		} else {
			$this->newspaper_group_array_id = $shipperArray;
		}
	}
	
	
	public function getModuleTranslations()
	{
		return array('name' => 'Council');
	}
   
	public static function getCount($params = array())
	{
		$session = erLhcoreClassAbstract::getSession();
		
		$q = $session->database->createSelectQuery();  
		
		$q->select( "COUNT(id)" )->from( "lh_abstract_council" );   
	 
		if (isset($params['filter']) && count($params['filter']) > 0)
		{
	   		$conditions = array();
	   
		   	foreach ($params['filter'] as $field => $fieldValue)
		   	{
		    	$conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
		   	}
	   
	   		$q->where( $conditions );
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
	   		
	   	case 'newspaper_group_array_id':
	   			$this->newspaper_group_array_id = $this->getNewspaperGroupAssigned();
	   			return $this->newspaper_group_array_id;
	   			break;
	   		
	   	case 'newspaper_group':
	   			$this->newspaper_group = '';
       	       
       	        $shippers = $this->getNewspaperGroupAssigned(true);
       	        $parts = array();
       	        foreach ($shippers as $shipper)
       	        {
       	           $parts[] = $shipper->name;
       	        }
       	              	            	       
       		    $this->newspaper_group = implode(', ',$parts);
       	             	       
       		    return $this->newspaper_group;
       		    
	   			break;
	   			
	   	default:
	   		break;
	   }
	}
	
	public function getNewspaperGroupAssigned($fetchObjects = false)
	{
		if ($this->id > 0) {
			$session = erLhcoreClassAbstract::getSession();
			$q = $session->database->createSelectQuery();
			$q->select( "newspaper_group_id" )->from( "lh_abstract_newspapergroup_council" );
			$q->where(
					$q->expr->eq( 'council_id', $this->id )
			);
			$stmt = $q->prepare();
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
			 
			if ($fetchObjects == true && $result !== false && count($result) > 0){
				return erLhAbstractModelNewspaperGroup::getList(array('filterin' => array('id' => $result)));
			}
			 
			return $result;
		}
		 
		return array();
	}
	
	public static function fetch($id)
	{   
		if (isset($GLOBALS['erLhAbstractModelCouncil_'.$id])) return $GLOBALS['erLhAbstractModelCouncil_'.$id];         
	
		try {              
			$GLOBALS['erLhAbstractModelCouncil_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelCouncil', (int)$id );     
		} catch (Exception $e) {
			$GLOBALS['erLhAbstractModelCouncil_'.$id] = '-';  
		}
	
		return $GLOBALS['erLhAbstractModelCouncil_'.$id];
	}
   
	public function removeThis()
	{
		$this->assignNewspaperGroupToCouncil(array());
		erLhcoreClassAbstract::getSession()->delete($this);
	}
   
	public static function getList($paramsSearch = array())
   	{             
       	$paramsDefault = array('limit' => 5000, 'offset' => 0);
       
       	$params = array_merge($paramsDefault,$paramsSearch);
       
       	$session = erLhcoreClassAbstract::getSession();
       
       	$q = $session->createFindQuery( 'erLhAbstractModelCouncil' );  
       
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
			$q->where( $conditions );
		} 
      
      	$q->limit($params['limit'],$params['offset']);
                
      	$q->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, name ASC' ); 
              
       	$objects = $session->find( $q );
         
    	return $objects; 
	}
   
	public static function validateCouncil($council) {
	
		$councilSearch = erLhcoreClassCharTransform::TransformToURL($council);
		 
		$valid = ($councilSearch != '' && self::getCount(array('filter' => array('aname' => $councilSearch))) > 0);
		
		if ( $valid == true ) {
			return $council;
		}
		
		return false;		
	}
	
	public static function getcouncil($council)
	{
		$list = self::getList(array('filter' => array('aname' => $council)));
		
		if ( !empty($list) ) {
			return array_shift($list);
		}
	}
	
   	public $id = null;
	public $name = '';
	public $aname = '';
	public $location = '';
	public $lat = 0;
	public $lon = 0;
	public $telephone = '';
	public $other = 0;
	public $position = 0;
	
}

?>