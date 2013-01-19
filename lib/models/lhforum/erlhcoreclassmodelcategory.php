<?

class erLhcoreClassModelForumCategory {
        
   public function getState()
   {
       return array(
               'id'             => $this->id,
               'description'    => $this->description,             
               'name'           => $this->name,             
               'placement'      => $this->placement,             
               'parent'         => $this->parent,         
               'user_id'        => $this->user_id,         
               'message_count'  => $this->message_count,
               'topic_count'    => $this->topic_count,      
               'last_topic_id'  => $this->last_topic_id,      
       );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   
   public static function isCategoryOwner($aid,$skipChecking = false)
   {
       $category = erLhcoreClassModelForumCategory::fetch($aid);
                   
       if ($skipChecking==true) return $category;
                         
       $currentUser = erLhcoreClassUser::instance();              
       if ($category->user_id == $currentUser->getUserID()) return $category;
        
       return false;  
   }
   
   public function saveThis()
   {              
       erLhcoreClassForum::getSession()->saveOrUpdate($this);    
       $this->clearCategoryCache();
   }
   
   public static function fetch($cid)
   {
       $Category = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumCategory', (int)$cid );
       return $Category;
   }
   
   public static function getList($paramsSearch = array())
   {       
        $paramsDefault = array('limit' => 8, 'offset' => 0);
        
        $params = array_merge($paramsDefault,$paramsSearch);
        
        $conditions = array();
        
        $session = erLhcoreClassForum::getSession('slave');
        $q = $session->createFindQuery( 'erLhcoreClassModelForumCategory' ); 
           
        if (isset($params['filter']) && count($params['filter']) > 0)
        {                     
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue ));
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
               $conditions[] = $q->expr->lt( $field, $q->bindValue($fieldValue ));
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
                
        $q->orderBy(isset($params['sort']) ? $params['sort'] : 'placement ASC, id ASC' ); 
          
        if (!isset($params['use_iterator'])){
            $objects = $session->find( $q ); 
        } else {
            $objects = $session->findIterator( $q );
        }
        
        return $objects; 
   }
   
   public function removeThis()
   {   
       // Remove child topics
       foreach (erLhcoreClassModelForumTopic::getList(array('filter' => array('path_'.$this->depth => $this->id))) as $topic){
           $this->removeThis();
       }       
       
       // Remove child category
       foreach (self::getList(array('filter' => array('parent' => $this->id))) as $category)
       {
           $category->removeThis();
       }
       
       erLhcoreClassForum::getSession()->delete($this);
       
       $this->clearCategoryCache();
   }
   
   public static function fetchCategoryColumn($params = array(),$column = 'COUNT(id)')
   {
       $session = erLhcoreClassForum::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( $column )->from( "lh_forum_category" );     
         
       $conditions = array();
       
       if (isset($params['filter']) && count($params['filter']) > 0)
       {
           foreach ($params['filter'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->eq( $field, $q->bindValue( $fieldValue ) );
           } 
      }  
      
      if (isset($params['filterin']) && count($params['filterin']) > 0)
       {
           foreach ($params['filterin'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->in( $field,   $fieldValue );
           } 
      }     
       
      if (isset($params['filterlt']) && count($params['filterlt']) > 0)
       {
           foreach ($params['filterlt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->lt( $field, $q->bindValue( $fieldValue ) );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue( $fieldValue ) );
           } 
      }
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      }         
            
      $stmt = $q->prepare();       
      $stmt->execute();
      $result = $stmt->fetchColumn();
      
      return $result; 
   }
        
   
   public static function getCategoryPath(& $array,$category_id)
   {     
      $cache = CSCacheAPC::getMem();
      $cacheKey = md5('forum_version_'.$cache->getCacheVersion('forum_category_'.$category_id).'category_path_'.$category_id.'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);
      
      if (($path = $cache->restore($cacheKey)) === false) {
         self::calculatePath($array,$category_id);
         $cache->store($cacheKey,$array);
      } else {
         $array = $path;        
      } 
   }
   
   public static function getCategoryPathURL(& $array,$category_id)
   {     
      $cache = CSCacheAPC::getMem();
      $cacheKey = md5('forum_version_'.$cache->getCacheVersion('forum_category_'.$category_id).'category_path_url'.$category_id); 
         
      if (($path = $cache->restore($cacheKey)) === false)
      {     
         erLhcoreClassModelForumCategory::calculatePathURL($array,$category_id);
         $cache->store($cacheKey,$array);
      } else {
         $array = $path;        
      } 
   }
   
   public static function calculatePath(& $array,$category_id){
       static $recursionProtect = 0;
       
       $category = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumCategory', (int)$category_id );
       
       $array[] = array('url' => $category->path_url,'title' => $category->name);   
           
       if ($category->parent != 0){
          self::calculatePath($array,$category->parent); 
       } else {
          $array = array_reverse($array); 
       }
       $recursionProtect++; 
       
       if ($recursionProtect > 500) exit;
   }

   public static function calculatePathObjects(& $array,$category_id){
       static $recursionProtect = 0;
       
       $category = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumCategory', (int)$category_id );
       
       $array[] = $category;   
           
       if ($category->parent != 0){
          self::calculatePathObjects($array,$category->parent); 
       } else {
          $array = array_reverse($array); 
       }
       $recursionProtect++; 
       
       if ($recursionProtect > 500) exit;
   } 
   
   public static function calculatePathURL(& $array,$category_id){
       static $recursionProtect = 0;
       
       $category = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumCategory', (int)$category_id );
       
       $array[] = array('title' => $category->name);   
           
       if ($category->parent != 0){
          self::calculatePathURL($array,$category->parent); 
       } else {
          $array = array_reverse($array); 
       }
       $recursionProtect++; 
       
       if ($recursionProtect > 500) exit;
   }
   
   public function __get($variable)
   {
       switch ($variable) {
       	       	
       	case 'path':
       	            $arrayPath = array();
                    self::getCategoryPathURL($arrayPath,$this->id);
                    $this->path = $arrayPath;
                    return $this->path;
       	    break;
       	    
       	case 'path_site':
       	            $arrayPath = array();
                    self::getCategoryPath($arrayPath,$this->id);
                    $this->path_site = $arrayPath;
                    return $this->path_site;
       	    break;
       	        
       	case 'depth':
       	            $this->depth = count($this->path)-1;
       	            return $this->depth;
       	    break;

       	case 'parent_object':       	    
       	           $this->parent_object = false;
       	           if ($this->parent > 0) {
       	               $this->parent_object = self::fetch($this->parent);
       	           }
       	           return $this->parent_object;
       	           break;
       	    break;
       	            
       	case 'path_objects':
       	            $this->path_object = array();
       	            self::calculatePathObjects($this->path_object,$this->id);       	          
       	            return $this->path_object;
       	    break; 
       	            
       	case 'has_subcategorys':
       	            $this->has_subcategorys = false; 
       	            $this->has_subcategorys = is_array($this->subcategorys) && count($this->subcategorys) > 0 ? true : false;         	                  	                    	          
       	            return $this->has_subcategorys;
       	    break;
       		
       	case 'subcategorys':
       	        $this->subcategorys = self::getList(array('filter' => array('parent' => $this->id)));
       	        return $this->subcategorys;
       	    break;
       	    	
       	case 'last_topic':
       	        $this->last_topic = false;
       	        
       	        if ($this->last_topic_id > 0){
       	            try {
           	            $this->last_topic = erLhcoreClassModelForumTopic::fetch($this->last_topic_id);
       	            } catch (Exception $e) {
       	                $this->last_topic = false;
       	            }
       	        }
       	        
       	        return $this->last_topic;
       	    break;
       	    
       	case 'path_url':            
            if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'nice_url_enabled' ) == true)  {
                $this->path_url = erLhcoreClassDesign::baseurl($this->nice_path_base,false);                     
            } else {
                $this->path_url = erLhcoreClassDesign::baseurl('forum/category').'/'.$this->id;
            }
            
            return $this->path_url;
            break;  
                                  
         
       	case 'nice_path_base':
       	     $pathElements = array();
             foreach ($this->path as $item){
                $pathElements[] = erLhcoreClassCharTransform::TransformToURL($item['title']);
             }
             $this->nice_path_base = implode('/',$pathElements).'-'.$this->id.'fc.html';
             return $this->nice_path_base;             
       	break;
       	       	
       	case 'url_path_base':
            if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'nice_url_enabled' ) == true) { 
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect($this->nice_path_base);                     
            } else {
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect('forum/category').'/'.$this->id;
            }
            
            return $this->url_path_base;
            break;
                              	    	
       	case 'last_message_category':   
           	    $this->last_message_category = false;   
           	    if ($this->last_topic !== false){
               	    $this->last_message_category = $this->last_topic->last_message;
               		return $this->last_message_category;
           	    }
           	    return $this->last_message_category;
       	    break;
       
       	default:
       		break;
       }
   }
   
   public function incTopicCounter() {

       $this->topic_count++;
       $this->saveThis();
       
       if ($this->parent_object !== false){
           $this->parent_object->incTopicCounter();
       }
   }
   
   public function decTopicCounter($last_topic_id) {

       $this->topic_count--;
       
       if ($this->last_topic_id != $last_topic_id) {                      
           $this->last_topic_id = $this->getCategoryLastTopic();
       }
       
       $this->saveThis();
       
       if ($this->parent_object !== false){
           $this->parent_object->decTopicCounter();
       }       
   }

   // Propogate changes accross parent categorys
   public function incCounterMessage($last_topic_id) {
       $this->last_topic_id = $last_topic_id;
       $this->message_count++;
       $this->saveThis();

       if ($this->parent_object !== false){
           $this->parent_object->incCounterMessage($this->last_topic_id);
       }      
   }
   
   public function getCategoryLastTopic()
   {
       // Update last_message_ctime
       $session = erLhcoreClassGallery::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "id" )->from( "lh_forum_topic" );
       $q->where( 
                     $q->expr->eq( 'path_'.$this->depth, $q->bindValue($this->id ))   
          );
       $q->limit(1,0);
       $q->orderBy('last_message_ctime DESC');
      
       $stmt = $q->prepare();       
       $stmt->execute();   
       $result = $stmt->fetchColumn();
       
       return $result;
   }
      
   public function clearCategoryCache() {
       
       CSCacheAPC::getMem()->increaseCacheVersion('forum_category_'.$this->id);
       
       if ($this->parent_object !== false) {
           $this->parent_object->clearCategoryCache();
       } else {
           CSCacheAPC::getMem()->increaseCacheVersion('forum_category_0');           
       }
   }
   
   public function decCounterMessage($last_topic_id) {

       $this->message_count--;
       
       // That means that one of the topic message was deleted and we need update current category last_topic_id
       if ($this->last_topic_id != $last_topic_id) {                      
           $this->last_topic_id = $this->getCategoryLastTopic();
       }
       
       $this->saveThis();
       
       if ($this->parent_object !== false){
           $this->parent_object->decCounterMessage($this->last_topic_id);
       } 
   }
   
   public $id = null;
   public $description = '';
   public $name = '';
   public $placement = 0;
   public $parent = 0;   
   public $user_id = 0;   
   public $topic_count = 0;
   public $message_count = 0;   
   public $last_topic_id = 0;

}


?>