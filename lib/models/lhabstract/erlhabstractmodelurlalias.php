<?

class erLhAbstractModelUrlAlias {
        
   public function getState()
   {
       return array(
           'id'        		 => $this->id,
           'url_destination' => $this->url_destination,
           'url_alias'       => $this->url_alias,
          
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
   


   public function getFields()
   {
   	//echo  'getfields_' ;exit;
       return array(
       'url_alias' => array(
       'type' => 'text',
       'trans' => 'URL Alias',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'url_destination' => array(
       'type' => 'text',
       'trans' => 'URL Destination',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ))        
       );
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'UrlAlias');
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_url_alias" );   
         
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
     

       	default:
       		break;
       }
   }
   
   public static function fetch($id)
   {   
       
	   	if (isset($GLOBALS['erLhAbstractModelUrlAlias_'.$id])) return $GLOBALS['erLhAbstractModelUrlAlias_'.$id];
   		
   		try {
        	$GLOBALS['erLhAbstractModelUrlAlias_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelUrlAlias', (int)$id );
       	} catch (Exception $e){
       		$GLOBALS['erLhAbstractModelUrlAlias_'.$id] = new erLhAbstractModelRegions(); 
       	}
        
       	return $GLOBALS['erLhAbstractModelRegions_'.$id];
       	
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
       $q = $session->createFindQuery( 'erLhAbstractModelUrlAlias' );  
       
       $conditions = array(); 
       if (!isset($paramsSearch['smart_select'])) {
             
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
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_abstract_url_alias' );
           
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
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'id ASC');
              
       }
              
       $objects = $session->find( $q );
         
      return $objects; 
   }
   
   public $id = null;
   public $url_alias = '';
   public $url_destination = '';

   

}



?>