<?

class erLhAbstractModelAttrItem {

   public function getState()
   {
       $stateArray = array (
           'id'             	=> $this->id,
           'group_id'       	=> $this->group_id,
           'position'       	=> $this->position,
           'value'              => $this->value,
           'value_short'        => $this->value_short,
           'parent_attr_item'   => $this->parent_attr_item,
           'identifier_value'   => $this->identifier_value,
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
       return $this->value;
   }
   
   public function defaultSort()
   {
      return 'group_id ASC, parent_attr_item ASC, lh_abstract_attr_item.value ASC';
   }
   
   public static function getAttrIdByGroupAndIdentifier($attr_group, $valueIdentifier)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT id FROM lh_abstract_attr_item WHERE group_id = :group_id AND identifier_value = :identifier_value');
       $stmt->bindValue( ':group_id',$attr_group);   
       $stmt->bindValue( ':identifier_value',$valueIdentifier);   
       $stmt->execute();
       
       $value = (int)$stmt->fetchColumn();
       return $value;
   }
   
   public static function getAttrByValueGroup($value,$group_id)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT * FROM lh_abstract_attr_item WHERE group_id = :group_id AND value = :value LIMIT 1');
       $stmt->bindValue( ':value',$value);   
       $stmt->bindValue( ':group_id',$group_id);   
       $stmt->execute();
       
       $value = $stmt->fetchAll(PDO::FETCH_CLASS);
       return $value;
   }
   
   public static function getByGroupValue($group_id, $value)
   {
       $db = ezcDbInstance::get();
       $stmt = $db->prepare('SELECT * FROM lh_abstract_attr_item WHERE group_id = :group_id AND value = :value');
       $stmt->bindValue( ':group_id',$group_id);   
       $stmt->bindValue( ':value',$value);   
       $stmt->execute();
       
       $value = $stmt->fetchAll(PDO::FETCH_CLASS);
       return $value;
   }
   
   public static function addItems(erLhAbstractModelAttrGroup $attrGroup, $values)
   {
       $items = array_filter(explode(',',$values));
       $position = 0;

       foreach ( $items as $itemParts )
       {
          $position = $position + 10;      
          $itemData = explode('|',trim($itemParts));
          $item = trim($itemData[0]);
          $itemShort = isset($itemData[1]) ? trim($itemData[1]) : '';
             
          $itemsArray = self::getList(array('filter' => array('group_id' => $attrGroup->id, 'value' => trim($item))));
          if ( empty($itemsArray) ) {             
            $itemData = new erLhAbstractModelAttrItem();
            $itemData->group_id = $attrGroup->id;
            $itemData->value = trim($item);
            $itemData->value_short = trim($itemShort);
            $itemData->identifier_value = erLhcoreClassCharTransform::TransformToURL($itemData->value);
            $itemData->position = $position;
            $itemData->saveThis();
          } else {
            $itemCurrent = current($itemsArray);
            $itemCurrent->identifier_value = erLhcoreClassCharTransform::TransformToURL($itemCurrent->value);
            $itemCurrent->value_short = trim($itemShort);
            $itemCurrent->position = $position;
            $itemCurrent->updateThis();
          }
       }
   } 
   
   public static function addItemsDepend(erLhAbstractModelAttrGroup $attrGroupChild, $attrGroupParent)
   {
       foreach (self::getList(array('filter' => array('group_id' => $attrGroupParent->id))) as $attrParent)
       {
           $attr = erLhcoreClassAdLootCategory::getDependAttributes($attrGroupParent->identifier, $attrParent->value);
           $attrValues = explode(",",$attr);
           $position = 0;
           
           foreach ($attrValues as $value) {
               $position = $position + 10;
               $items = self::getList(array('filter' => array('parent_attr_item' => $attrParent->id, 'value' => trim($value))));
               if ( empty($items) ) {
               	    $obj = new erLhAbstractModelAttrItem();
                    $obj->group_id = $attrGroupChild->id;
                    $obj->value = trim($value);
                    $obj->parent_attr_item = $attrParent->id;
                    $obj->position = $position;
                    $obj->identifier_value = erLhcoreClassCharTransform::TransformToURL($obj->value);
                    $obj->saveThis();
                } else {
                    $obj = current($items);
                    $obj->identifier_value = erLhcoreClassCharTransform::TransformToURL($obj->value);
                    $obj->position = $position;
                    $obj->updateThis();
                }
           }
       }       
   }
   
   public function saveThis()
   {
       erLhcoreClassAbstract::getSession()->save($this);
   }
   
   public function updateThis()
   {
       erLhcoreClassAbstract::getSession()->update($this);
   }
    
   public function getFields()
   {
       return array (
       'value' => array (
       'type' => 'text',
       'trans' => 'Value',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'value_short' => array (
       'type' => 'text',
       'trans' => 'Value short',
       'required' => false,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'identifier_value' => array (
       'type' => 'text',
       'trans' => 'Identifier value',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
        
       'position' => array (
       'type' => 'text',
       'trans' => 'Position',
       'required' => true,       
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )),
         
       'group_id' => array (
       'type' => 'combobox',
       'trans' => 'Group',
       'frontend' => 'group',
       'source' => 'erLhAbstractModelAttrGroup::getList',
       'params_call' => array('limit' => 1000, 'offset' => 0),
       'required' => true,
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        )),
        
       'parent_attr_item' => array (
       'type' => 'combobox',
       'trans' => 'Parent attribute',
       'frontend' => 'parent_attribute',
       'source' => 'erLhAbstractModelAttrItem::getList',
       'params_call' => array('limit' => 1000, 'offset' => 0),
       'required' => false,
       'validation_definition' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        )),
        
        
        );
   }
   
//   
//   'id'             	=> $this->id,
//           'group_id'       	=> $this->group_id,
//           'position'       	=> $this->position,
//           'value'              => $this->value,
//           'value_short'        => $this->value_short,
//           'parent_attr_item'   => $this->parent_attr_item,
//           'identifier_value'   => $this->identifier_value,
   
   public function __get($var)
   {
       switch ($var) {
       	case 'left_menu':
       	       $this->left_menu = '';
       		   return $this->left_menu;
       		break;
       		
       	case 'parent_attribute':
       	       $this->parent_attribute = '';
       	       if ($this->parent_attr_item > 0) {
       	            $this->parent_attribute = self::fetch($this->parent_attr_item);
       	       }
       		   return $this->parent_attribute;
       		break;
       		
       	case 'value_shorten':
       		   return !empty($this->value_short) ? $this->value_short : $this->value;
       		break;
       			
       	case 'group':
       	       $this->group = erLhAbstractModelAttrGroup::fetch($this->group_id);
       		   return $this->group;
       		break;
       	default:
       		break;
       }
   }
   
   public function getModuleTranslations()
   {
       return array('name' => 'Attribute items for groups');
   }
   
   public static function getCount($params = array())
   {       
       if (isset($params['enable_sql_cache']))
       {
              $sql = erLhcoreClassAd::multi_implode(',',$params); 
              $cache = CSCacheAPC::getMem();

              $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('attr_item_count_'.$cache->getCacheVersion('site_attributes_version').$sql);

              if (($result = $cache->restore($cacheKey)) !== false)
              {              
                  return $result;
              }
       }       
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_attr_item" );   
         
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

      if (isset($params['enable_sql_cache'])) {
              $cache->store($cacheKey, $result);           
      } 
         
      return $result; 
   }
   
   public static function fetch($id)
   {   
       if (isset($GLOBALS['erLhAbstractModelAttrItem_'.$id])) return $GLOBALS['erLhAbstractModelAttrItem_'.$id];         
       $GLOBALS['erLhAbstractModelAttrItem_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelAttrItem', (int)$id );
       
       return $GLOBALS['erLhAbstractModelAttrItem_'.$id];
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
              $sql = erLhcoreClassAd::multi_implode(',',$params); 
              $cache = CSCacheAPC::getMem();

              $cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('attr_item_list_'.$cache->getCacheVersion('site_attributes_version').$sql);

              if (($result = $cache->restore($cacheKey)) !== false)
              {              
                  return $result;
              }
       }
       
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->createFindQuery( 'erLhAbstractModelAttrItem' );  
       
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
                  
                  $q->innerJoin('lh_abstract_attr_group', $q->expr->eq( 'lh_abstract_attr_group.id', 'lh_abstract_attr_item.group_id' ));
                  
                  
                  if (count($conditions) > 0)
                  {
                      $q->where( 
                                 $conditions   
                      );
                  }
                  
                  $q->limit($params['limit'],$params['offset']);
                            
                  $q->orderBy(isset($params['sort']) ? $params['sort'] : 'lh_abstract_attr_group.position, lh_abstract_attr_item.position ASC, lh_abstract_attr_item.value ASC' ); 
                  
       } else {
           $q2 = $q->subSelect();
           $q2->select( 'id' )->from( 'lh_abstract_attr_item' );
           
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
          $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'position ASC, value ASC');
          $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_abstract_attr_item.id', 'items.id' );          
       }
              
       $objects = $session->find( $q );
       
      if (isset($params['enable_sql_cache'])) {
              $cache->store($cacheKey, $objects);           
      } 
       
      return $objects; 
   }
   
   public $id = null;
   public $group_id = 0;
   public $position = 0;
   public $parent_attr_item = 0;
   public $value = '';
   public $value_short = '';
   public $identifier_value = '';
}



?>