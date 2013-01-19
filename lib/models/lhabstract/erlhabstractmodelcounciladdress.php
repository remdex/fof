<?

class erLhAbstractModelCouncilAddress {
        
	public function getState()
	{
		return array (
			'id'         		=> $this->id,
			'contact_person'    => $this->contact_person,		
			'email'       		=> $this->email,
			'telephone'       	=> $this->telephone,		
			'address1'      	=> $this->address1,		
			'address2'       	=> $this->address2,		
			'council_id'       	=> $this->council_id		
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
		return $this->contact_person;
	}   

   	public function getFields()
   	{
       	return array(
       	'council_id' => array (
       					'type' => 'combobox',
       					'trans' => 'Council',
       					'frontend' => 'council',
       					'source' => 'erLhAbstractModelCouncil::getList',
       					'params_call' => array('limit' => 5000, 'offset' => 0),
       					'required' => false,
       					'validation_definition' => new ezcInputFormDefinitionElement(
       							ezcInputFormDefinitionElement::OPTIONAL, 'int'
       	)),
		'contact_person' => array(
		'type' => 'text',
		'trans' => 'Who',
		'required' => true,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),       			
		'email' => array(
		'type' => 'text',
		'trans' => 'E-mail',
		'required' => false,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),       			
		'telephone' => array(
		'type' => 'text',
		'trans' => 'Telephone',
		'required' => false,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),       			
		'address1' => array(
		'type' => 'text',
		'trans' => 'Address 1',
		'required' => false,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),       			
		'address2' => array(
		'type' => 'text',
		'trans' => 'Address 2',
		'required' => false,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		))
       	);
	}
   
		
	
	public function getModuleTranslations()
	{
		return array('name' => 'Council address');
	}
   
	public static function getCount($params = array())
	{
		$session = erLhcoreClassAbstract::getSession();
		
		$q = $session->database->createSelectQuery();  
		
		$q->select( "COUNT(id)" )->from( "lh_abstract_council_address" );   
	 
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
	   		
	   		/*'id'         		=> $this->id,
	   		'contact_person'    => $this->contact_person,
	   		'email'       		=> $this->email,
	   		'telephone'       	=> $this->telephone,
	   		'address1'      	=> $this->address1,
	   		'address2'       	=> $this->address2,
	   		'council_id'       	=> $this->council_id*/
	   		
	   	case 'contact_in_notice':
	   			$parts = array();
	   			$parts[] = $this->contact_person;
	   			$parts[] = $this->email;
	   			$parts[] = $this->telephone;
	   			$parts[] = $this->address1;
	   			$parts[] = $this->address2;	   			
	   			$parts = array_filter($parts);
	   			
	   			return implode(', ', $parts);
	   		break;
	   			
	   	case 'council':
	   			$this->council = false;
	   				 
	   			if ( $this->council_id > 0 ) {
	   				try {
	   					$this->council = erLhAbstractModelCouncil::fetch($this->council_id);
	   				} catch (Exception $e) {
	   					 
	   				}
	   			}
	   			
	   			return $this->council;
	   			break;
	   	
	   			
	   	default:
	   		break;
	   }
	}
	

	
	public static function fetch($id)
	{   
		if (isset($GLOBALS['erLhAbstractModelCouncilAddress_'.$id])) return $GLOBALS['erLhAbstractModelCouncilAddress_'.$id];         
	
		try {              
			$GLOBALS['erLhAbstractModelCouncilAddress_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelCouncilAddress', (int)$id );     
		} catch (Exception $e) {
			$GLOBALS['erLhAbstractModelCouncilAddress_'.$id] = '-';  
		}
	
		return $GLOBALS['erLhAbstractModelCouncilAddress_'.$id];
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
       
       	$q = $session->createFindQuery( 'erLhAbstractModelCouncilAddress' );  
       
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
                
      	$q->orderBy(isset($params['sort']) ? $params['sort'] : 'id ASC' ); 
              
       	$objects = $session->find( $q );
                     	
    	return $objects; 
	}
   
   	public $id = null;
   	public $contact_person = '';
   	public $email = '';
   	public $telephone = '';
   	public $address1 = '';
   	public $address2 = '';
   	public $council_id = NULL;
}

?>