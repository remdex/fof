<?

class erLhAbstractModelRegions {
        
   public function getState()
   {
       return array(
           'id'         => $this->id,
           'name'       => $this->name,
           'position'   => $this->position,
           'visible'    => $this->visible,
           'lon' 		=> $this->lon,
           'lat' 		=> $this->lat,
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
   
   public function deleteImage()
   {
   		$photoDir = 'var/makerslogos_truck/'.$this->id.'/'.$this->filename;
   		unlink($photoDir);
   		$this->filename = '';
   }
   
   public function movePhoto()
   {	
   		$config = erConfigClassLhConfig::getInstance();
   		$photoDir = 'var/makerslogos_truck/'.$this->id;
		if (!file_exists($photoDir))
		mkdir($photoDir,$config->conf->getSetting( 'site', 'StorageDirPermissions' ));

   		$fileName = erLhcoreClassValidationHelpher::moveUploadedFile('AbstractInput_filename', $photoDir . '/' );

   		$this->filename = $fileName;	       
   }
     
   public function getFields()
   {
   	//echo  'getfields_' ;exit;
       return array(
       'name' => array(
       'type' => 'text',
       'trans' => 'Name',
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
     
       'visible' => array(
       'type' => 'checkbox',
       'trans' => 'Visible',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ))
       
       );
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'Regions');
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_regions" );   
         
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
       		
       case 'body_array_id':       	       
       	       $this->body_array_id = $this->getBodyAssigned();       	             
       		   return $this->body_array_id;
       		break; 
  	

       case 'body':       	       
       	       $this->body = '';
       	       
       	       $shippers = $this->getBodyAssigned(true);
       	       $parts = array();
       	       foreach ($shippers as $shipper)
       	       {
       	           $parts[] = $shipper->name;
       	       }
       	              	            	       
       		   $this->body = implode(', ',$parts);
       	             	       
       		   return $this->body;
       		break;

       	default:
       		break;
       }
   }
   
   public static function fetch($id)
   {   
       
	   	if (isset($GLOBALS['erLhAbstractModelRegions_'.$id])) return $GLOBALS['erLhAbstractModelRegions_'.$id];
   		
   		try {
        	$GLOBALS['erLhAbstractModelRegions_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelRegions', (int)$id );
       	} catch (Exception $e){
       		$GLOBALS['erLhAbstractModelRegions_'.$id] = new erLhAbstractModelRegions(); 
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
       $q = $session->createFindQuery( 'erLhAbstractModelRegions' );  
       
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
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, name ASC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_abstract_regions' );
           
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
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, name ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_truck_make.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );
         
      return $objects; 
   }
   
   public $id = null;
   public $name = '';
   public $lat = 0;
   public $position = 0;
   public $visible = 1;
   public $lon = 0;
   

}



?>