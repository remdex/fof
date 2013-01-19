<?

class erLhcoreClassModelForumFile {
        
    public function getState()
   {
       return array(
               'id'         => $this->id,
               'name'       => $this->name,             
               'file_path'  => $this->file_path,             
               'file_size'  => $this->file_size,             
               'message_id' => $this->message_id         
       );
   }

   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }

   public static function fetch($aid)
   {    
       $topic = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumFile', (int)$aid );     
       return $topic;
   } 

   public function removeThis()
   {          
       if (file_exists($this->file_path . '/' . $this->name)){
           @unlink($this->file_path . '/' . $this->name);
       }

       erLhcoreClassImageConverter::removeRecursiveIfEmpty('var/forum/',str_replace('var/forum/','',$this->file_path));

       erLhcoreClassForum::getSession()->delete($this); 
   }

   public function saveThis()
   {       
       erLhcoreClassForum::getSession()->saveOrUpdate($this); 
   }

     
   public function __get($variable)
   {
       switch ($variable) {
       	/*case 'images_count':
       	       $this->images_count = erLhcoreClassModelGalleryImage::getImageCount(array('cache_key' => CSCacheAPC::getMem()->getCacheVersion('album_'.$this->aid),'filter' => array('aid' => $this->aid,'approved' => 1)));
       		   return $this->images_count;
       		break;*/        				
             
       	default:
       		break;
       }
   }
            
   public static function getCount($params = array())
   {
       $session = erLhcoreClassForum::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_forum_file" );   
         
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           $conditions = array();
           
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue ));
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
       $paramsDefault = array('limit' => 8, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassForum::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelForumFile' );  

       $conditions = array();

       $q2 = $q->subSelect();
       $q2->select( 'id' )->from( 'lh_forum_file' );
       
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
      
      if (isset($params['filterlte']) && count($params['filterlte']) > 0)
      {
           foreach ($params['filterlte'] as $field => $fieldValue)
           {
               $conditions[] = $q2->expr->lte( $field, $q->bindValue($fieldValue) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
      {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q2->expr->gt( $field,$q->bindValue( $fieldValue) );
           } 
      } 
      
      if (isset($params['filtergte']) && count($params['filtergte']) > 0)
      {
           foreach ($params['filtergte'] as $field => $fieldValue)
           {
               $conditions[] = $q2->expr->gte( $field,$q->bindValue( $fieldValue) );
           } 
      }      
       
      if (count($conditions) > 0)
      {
          $q2->where( 
                     $conditions   
          );
      }
      
      if (isset($params['use_index'])) {         
        $q2->useIndex( $params['use_index'] );
      }
      
      $q2->limit($params['limit'],$params['offset']);
      $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC');
      
      $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_forum_file.id', 'items.id' );          
            
      $objects = $session->find( $q );         
      return $objects; 
   }
   
   public $id = null;
   public $name = '';
   public $file_path = '';
   public $file_size = 0;
   public $message_id = 0;
}