<?

class erLhAbstractModelAdZoneNewspaper {
        
   public function getState()
   {
       return array(
           'id'         		=> $this->id,
           'ad_id'       		=> $this->ad_id,
           'newspaper_id'    	=> $this->newspaper_id,
           'newspaper_group_id' => $this->newspaper_group_id
       );
   }
      
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
    
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_abstract_ad_zone_newspaper" );   
         
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
       if (isset($GLOBALS['erLhAbstractModelAdZoneNewspaper_'.$id])) return $GLOBALS['erLhAbstractModelAdZoneNewspaper_'.$id];
       $GLOBALS['erLhAbstractModelAdZoneNewspaper_'.$id] = erLhcoreClassAbstract::getSession()->load( 'erLhAbstractModelAdZoneNewspaper', (int)$id );
       
       return $GLOBALS['erLhAbstractModelAdZoneNewspaper_'.$id];
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
       $q = $session->createFindQuery( 'erLhAbstractModelAdZoneNewspaper' );  
            
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
   public $ad_id = null;
   public $newspaper_id = 0;
   public $newspaper_group_id = 0;
   
}

?>