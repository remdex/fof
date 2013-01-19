<?

class erLhcoreClassModelOidMap {
        
   public function getState()
   {
       return array(
               'open_id'        => $this->open_id,
               'user_id'        => $this->user_id,            
               'open_id_type'   => $this->open_id_type,            
               'email'          => $this->email,            
               'id'             => $this->id,            
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }

   public static function fetch($id)
   {
       $open_id = erLhcoreClassUser::getSession('slave')->load( 'erLhcoreClassModelOidMap', (int)$id );
       return $open_id;
   }
     
   public static function isOwner($id, $skipChecking = false)
   {
       $open_id = self::fetch($id);
       
       if ($skipChecking==true) return $open_id;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($open_id->user_id == $currentUser->getUserID()) return $open_id;        
       return false;  
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassUser::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_oid_map" );   
         
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
            
      return $result; 
   }
   
   public static function getList($paramsSearch = array())
   {             
       $paramsDefault = array('limit' => 32, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassUser::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelOidMap' );  
       
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
               $conditions[] = $q->expr->gt( $field,$q->bindValue( $fieldValue ));
           } 
      }      
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
      
      $q->limit($params['limit'],$params['offset']);
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'open_id ASC' ); 
      
              
       $objects = $session->find( $q );
         
      return $objects; 
   }
   
   public function saveThis()
   {
       erLhcoreClassUser::getSession()->save( $this );
   }
   
   public function removeThis()
   {
       erLhcoreClassUser::getSession()->delete( $this );
   }
    
   public $id = null;
   public $open_id = null;
   public $user_id = null;
   public $open_id_type = null;
   public $email = '';

   const OPEN_ID_GOOGLE = 1;
   const OPEN_ID_FACEBOOK = 2;
   const OPEN_ID_TWITTER = 3;
}



?>