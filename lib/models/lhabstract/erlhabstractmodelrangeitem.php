<?

class erLhAbstractModelRangeItem {
        
   public function getState()
   {
       return array(
           'id'         => $this->id,
           'value'      => $this->value,
           'position'   => $this->position,
           'group_id'   => $this->group_id,
           'checked'   => $this->checked,
       );
   }
/*   SELECT people.id
         , people.name
      FROM people
    INNER
      JOIN people_like_artist
        ON people_like_artist.people_id = people.id 
       AND people_like_artist.artist_id IN (1,3)
    GROUP 
        BY people.id
    HAVING COUNT(*) = 2 -- number of artists in the join condition*/

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
       'value' => array(
                     'type' => 'text',
          'trans' => 'Value',
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
       ),
       'group_id' => array (
       'type' => 'combobox',
       'trans' => 'Group',
       'frontend' => 'group',
       'source' => 'erLhAbstractModelRangeGroup::getList',
       'params_call' => array('limit' => 1000, 'offset' => 0),
       'required' => true,
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        )),        
        'checked' => array(
           'type' => 'checkbox',
           'trans' => 'Selected by default',
           'required' => true,
           'validation_definition' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
            )));
   }
   
   public function __get($var)
   {
       switch ($var) {
       	case 'group':
       	       $this->group = '';
       	       if ($this->group_id > 0) {
       	            $this->group = erLhAbstractModelRangeGroup::fetch($this->group_id);
       	       }
       		   return $this->group;
       		break;
       	case 'name':
       	        return $this->value;
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
       return array('name' => 'Range attributes');
   }
      
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_range_item" );   
         
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
       if (isset($GLOBALS['erLhAbstractModelRangeItem_'.$id])) return $GLOBALS['erLhAbstractModelRangeItem_'.$id];         
       $GLOBALS['erLhAbstractModelRangeItem_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelRangeItem', (int)$id );
       
       return $GLOBALS['erLhAbstractModelRangeItem_'.$id];
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
       $q = $session->createFindQuery( 'erLhAbstractModelRangeItem' );  
            
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
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'group_id ASC, position ASC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_abstract_range_item' );
           
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
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'group_id ASC, position ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_range_item.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );
         
      return $objects; 
   }
   
   public $id = null;
   public $value = '';
   public $position = 0;
   public $group_id = 0;
   public $checked = 0;
   

}



?>