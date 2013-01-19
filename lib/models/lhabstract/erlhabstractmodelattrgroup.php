<?

class erLhAbstractModelAttrGroup {
        
   public function getState()
   {
       $stateArray = array (
           'id'             	=> $this->id,
           'position'       	=> $this->position,
           'display_name'   	=> $this->display_name,
           'identifier'     	=> $this->identifier,
           'identifier_value'   => $this->identifier_value,
           'data_type'      	=> $this->data_type,
           'defaultvalue'   	=> $this->defaultvalue,
           'parent_id'      	=> $this->parent_id,
           'has_child'      	=> $this->has_child
       );
       
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
       return $this->display_name;
   }   
   
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->saveOrUpdate($this);
   }
   
   public function updateThis()
   {
       erLhcoreClassAbstract::getSession()->update($this);
   }
   
   public static function addAttrGroup(stdClass $attrGroup, $counter = 0)
   {
       $attrGroupData = self::getList(array('filter' => array('identifier' => $attrGroup->Name)));
       
       if ( !empty($attrGroupData) ) {
           $attrGroupItem = current($attrGroupData);           
           $attrGroupItem->display_name = empty($attrGroup->DisplayName) ? $attrGroup->Name : $attrGroup->DisplayName;
           $attrGroupItem->identifier_value = erLhcoreClassCharTransform::TransformToURL($attrGroupItem->display_name);
           $attrGroupItem->position = $counter * 10;
           //$attrGroupItem->no_index = ( isset($attrGroup->NoIndexForSEO) && ($attrGroup->NoIndexForSEO == 'true' || $attrGroup->NoIndexForSEO == 1) ) ? 1 : 0;
           $attrGroupItem->updateThis();
       } else {
           $attrGroupItem = new erLhAbstractModelAttrGroup();
           $attrGroupItem->data_type = $attrGroup->DataType;
           $attrGroupItem->identifier = $attrGroup->Name;
           $attrGroupItem->display_name = empty($attrGroup->DisplayName) ? $attrGroup->Name : $attrGroup->DisplayName;
           $attrGroupItem->position = $counter;
           //$attrGroupItem->no_index = ( isset($attrGroup->NoIndexForSEO) && ($attrGroup->NoIndexForSEO == 'true' || $attrGroup->NoIndexForSEO == 1) ) ? 1 : 0;
           $attrGroupItem->identifier_value = erLhcoreClassCharTransform::TransformToURL($attrGroupItem->display_name);
           $attrGroupItem->saveThis();
       }
       
       switch ($attrGroupItem->data_type) {
       	case 'enum':
           		erLhAbstractModelAttrItem::addItems($attrGroupItem, $attrGroup->Values);
       		break;
       		
       	case 'boolean':
           		$attrGroupItem->defaultvalue = $attrGroup->DefaultValue;
           		$attrGroupItem->saveThis();
       		break;
       
       	default:
       		break;
       }
              
       if ( is_array($attrGroup->ChildProperties) && !empty($attrGroup->ChildProperties) ) {
           
           $attrGroupItem->has_child = 1;
           $attrGroupItem->saveThis();
           
           foreach ($attrGroup->ChildProperties as $childAttribute) {
               
               $attrGroupData = self::getList(array('filter' => array('identifier' => trim($childAttribute->Name))));
           
               if ( !empty($attrGroupData) ) {
                   $childAttrGroupItem = current($attrGroupData);           
                   $childAttrGroupItem->display_name = 'Car Model'/*empty($childAttribute->DisplayName) ? $childAttribute->Name : $childAttribute->DisplayName*/;
                   $childAttrGroupItem->identifier_value = erLhcoreClassCharTransform::TransformToURL($childAttrGroupItem->display_name);
                   $childAttrGroupItem->position = ($counter * 10) + 5;                   
                   $childAttrGroupItem->updateThis();
               } else {
                   $childAttrGroupItem = new erLhAbstractModelAttrGroup();
                   $childAttrGroupItem->data_type = $childAttribute->DataType;
                   $childAttrGroupItem->identifier = trim($childAttribute->Name);
                   $childAttrGroupItem->display_name = 'Car Model'/*empty($childAttribute->DisplayName) ? $childAttribute->Name : $childAttribute->DisplayName*/;
                   $childAttrGroupItem->position = ($counter * 10) + 5;
                   $childAttrGroupItem->parent_id = $attrGroupItem->id;
                   $childAttrGroupItem->identifier_value = erLhcoreClassCharTransform::TransformToURL($childAttrGroupItem->display_name);
                   $childAttrGroupItem->saveThis();
               }
               
               switch ($childAttrGroupItem->data_type) {
               case 'enum':
                  		erLhAbstractModelAttrItem::addItemsDepend($childAttrGroupItem, $attrGroupItem);
              		break;
                             
                default:
               		break;
               }               
           }
       }
       
       
       return (int)$attrGroupItem->id;
   }
    
   public function getFields()
   {
       return array (
       'identifier' => array (
       'type' => 'text',
       'trans' => 'Identifier',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
       'display_name' => array(
       'type' => 'text',
       'trans' => 'Display name',
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
        
       'identifier_value' => array(
       'type' => 'text',
       'trans' => 'Identifier value',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'data_type' => array(
       'type' => 'text',
       'trans' => 'Datatype',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'defaultvalue' => array(
       'type' => 'text',
       'trans' => 'Default value',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'parent_id' => array(
       'type' => 'combobox',
       'trans' => 'Parent attribute group',
       'required' => false,
       'frontend' => 'parent',
       'source' => 'erLhAbstractModelAttrGroup::getList',
       'params_call' => array('limit' => 1000, 'offset' => 0),       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        )),
        
       'has_child' => array(
       'type' => 'checkbox',
       'trans' => 'Has childs',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        )),
        
       /*'no_index' => array(
       'type' => 'checkbox',
       'trans' => 'No index',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        )) */       
        
        );
   }
    
   public function __get($var)
   {
       switch ($var) {
       	case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       		
       	case 'parent':
       	       if ($this->parent_id > 0) {
       	            $this->parent = self::fetch($this->parent_id);
       	       } else {
       	            $this->parent = '';
       	       }
       		   return $this->parent;
       		break;
       		
       	default:
       		break;
       }
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'Attribute groups');
   }
   
   public static function getCount($params = array())
   {
       if (isset($params['enable_sql_cache']))
       {
              $sql = CSCacheAPC::multi_implode(',',$params); 
              $cache = CSCacheAPC::getMem();

              $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('attr_group_count_'.$cache->getCacheVersion('site_attributes_version').$sql);

              if (($result = $cache->restore($cacheKey)) !== false)
              {              
                  return $result;
              }
       }
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_attr_group" );   
         
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
       
      if (isset($params['enable_sql_cache'])) {
              $cache->store($cacheKey, $result);           
      } 
      
      return $result; 
   }
   
   public static function fetch($id)
   {   
       if (isset($GLOBALS['erLhAbstractModelAttrGroup_'.$id])) return $GLOBALS['erLhAbstractModelAttrGroup_'.$id];         
       $GLOBALS['erLhAbstractModelAttrGroup_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelAttrGroup', (int)$id );
       
       return $GLOBALS['erLhAbstractModelAttrGroup_'.$id];
   }
   
   public function removeThis()
   {
       erLhcoreClassAbstract::getSession()->delete($this);
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 500, 'offset' => 0);       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       if (isset($params['enable_sql_cache']))
       {
              $sql = CSCacheAPC::multi_implode(',',$params); 
              $cache = CSCacheAPC::getMem();

              $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('attr_group_list_'.$cache->getCacheVersion('site_attributes_version').$sql);

              if (($result = $cache->restore($cacheKey)) !== false)
              {              
                  return $result;
              }
       }
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelAttrGroup' );  
       
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
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, display_name ASC' ); 
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'pid' )->from( 'lh_abstract_range_group' );
           
           if (isset($params['filter']) && count($params['filter']) > 0)
          {                     
               foreach ($params['filter'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->eq( $field, $q->bindValue($fieldValue) );
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
                   $conditions[] = $q2->expr->lt( $field, $q->bindValue($fieldValue) );
               } 
          }
          
          if (isset($params['filtergt']) && count($params['filtergt']) > 0)
          {
               foreach ($params['filtergt'] as $field => $fieldValue)
               {
                   $conditions[] = $q2->expr->gt( $field, $q->bindValue($fieldValue) );
               } 
          }      
          
          if (count($conditions) > 0)
          {
              $q2->where( 
                         $conditions   
              );
          }
           
          $q2->limit($params['limit'],$params['offset']);
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, display_name ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_attr_group.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );
       
      if (isset($params['enable_sql_cache'])) {
              $cache->store($cacheKey, $objects);           
      } 
        
      return $objects; 
   }
   
   public static function getByIdentifier($identifier)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT * FROM lh_abstract_attr_group WHERE identifier = :identifier LIMIT 1');
       $stmt->bindValue( ':identifier',$identifier);   
       $stmt->execute();
       
       $value = $stmt->fetchAll(PDO::FETCH_CLASS);
       return $value;
   }
   
   
   
   
   public $id = null;
   public $position = 0;
   public $data_type = null;
   public $display_name = null;
   public $identifier = null;
   public $identifier_value = null;
   public $defaultvalue = '';
   public $parent_id = 0;
   public $has_child = 0;
   
   // This attribute is set from category
   public $no_index = 0;
   

   

}



?>