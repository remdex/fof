<?

class erLhAbstractModelEmailTemplate {
        
	public function getState()
	{
		$stateArray = array (
			'id'         => $this->id,		
			'from_name'  => $this->from_name,
			'from_email' => $this->from_email,			
			'name'       => $this->name		
		);
		
		foreach (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'available_locales' ) as $locale) {		
			$stateArray['subject_'.strtolower($locale)] = $this->{'subject_'.strtolower($locale)};
			$stateArray['content_'.strtolower($locale)] = $this->{'content_'.strtolower($locale)};
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
		'required' => true,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),         
		'from_name' => array(
		'type' => 'text',
		'trans' => 'From name',
		'required' => true,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),         
		'from_email' => array(
		'type' => 'text',
		'trans' => 'From email',
		'required' => true,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),         
		'subject' => array(
		'type' => 'text',
		'multilanguage' => true,
		'trans' => 'Subject',
		'required' => true,       
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)),         
		'content' => array(
		'type' => 'textarea',
		'trans' => 'Content',
		'required' => true,     
		'multilanguage' => true,
		'validation_definition' => new ezcInputFormDefinitionElement(
		    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
		)));
	}
   
	public function getModuleTranslations()
	{
		return array('name' => 'Email templates');
	}
   
	public static function getCount($params = array())
	{
		$session = erLhcoreClassAbstract::getSession();
		$q = $session->database->createSelectQuery();  
		$q->select( "COUNT(id)" )->from( "lh_abstract_email_templates" );   
	 
		if (isset($params['filter']) && count($params['filter']) > 0)
		{
	   		$conditions = array();
	   
		   	foreach ($params['filter'] as $field => $fieldValue)
		   	{
		    	$conditions[] = $q->expr->eq( $field, $fieldValue );
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
	   		
	   	case 'subject':
	   			$value = $this->{'subject_'.strtolower(erLhcoreClassSystem::instance()->Language)};
	   			if ($value != '') return $value;
	   			return $this->subject_en_en;
	   		
	   	case 'content':
	   			$value = $this->{'content_'.strtolower(erLhcoreClassSystem::instance()->Language)};
	   			if ($value != '') return $value;
	   			return $this->content_en_en;
	   			break;
	   			
	   	default:
	   		break;
	   }
	}
	
	public static function fetch($id)
	{   
		if (isset($GLOBALS['erLhAbstractModelEmailTemplate_'.$id])) return $GLOBALS['erLhAbstractModelEmailTemplate_'.$id];         
	
		try {              
			$GLOBALS['erLhAbstractModelEmailTemplate_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelEmailTemplate', (int)$id );     
		} catch (Exception $e) {
			$GLOBALS['erLhAbstractModelEmailTemplate_'.$id] = '-';  
		}
	
		return $GLOBALS['erLhAbstractModelEmailTemplate_'.$id];
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
       
       	$q = $session->createFindQuery( 'erLhAbstractModelEmailTemplate' );  
       
		$conditions = array(); 
                   
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
			$q->where( $conditions );
		} 
      
      	$q->limit($params['limit'],$params['offset']);
                
      	$q->orderBy(isset($params['sort']) ? $params['sort'] : 'id ASC' ); 
              
       	$objects = $session->find( $q );
         
    	return $objects; 
	}
   
   	public $id = null;
	public $name = '';
	public $subject_en_en = '';
	public $from_name = '';
	public $from_email = '';
	public $content_en_en = '';

}

?>