<?

class erLhcoreClassModelForumTopic {

   public function getState()
   {
       return array(
               'id'                 => $this->id,
               'ctime'              => $this->ctime,             
               'topic_name'         => $this->topic_name,             
               'path_1'             => $this->path_1,             
               'path_2'             => $this->path_2,             
               'path_3'             => $this->path_3,             
               'path_0'             => $this->path_0,             
               'user_id'            => $this->user_id,          
               'message_count'      => $this->message_count,          
               'last_message_ctime' => $this->last_message_ctime,          
               'topic_status'       => $this->topic_status,        
               'main_category_id'   => $this->main_category_id         
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
       return $this->topic_name;
   }

   /**
    * Small cache tricks
    * */
   public static function fetch($aid,$canUseCache = false)
   {    
       if ($canUseCache === true && isset($GLOBALS['ForumTopicCacheObject_'.$aid])) return $GLOBALS['ForumTopicCacheObject_'.$aid];
       
       $topic = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumTopic', (int)$aid );  
       
       if ($canUseCache === true){
           $GLOBALS['ForumTopicCacheObject_'.$aid] = $topic;
       }
          
       return $topic;
   } 

   public function removeThis()
   {          
       foreach (erLhcoreClassModelForumMessage::getList(array('filter' => array('topic_id' => $this->id))) as $message)
       {
           $message->removeThis();
       }

       erLhcoreClassForum::getSession()->delete($this);              
       $this->main_parent->decTopicCounter($this->id);
       
       $this->chearTopicCache();       
   }

   public function saveThis()
   {       
       if ($this->id == null) {
           erLhcoreClassForum::getSession()->save($this);
           $this->main_parent->incTopicCounter($this->id);
       } else {
           erLhcoreClassForum::getSession()->update($this); 
       }    
       
       $this->chearTopicCache();
   }

   public function chearTopicCache(){
       CSCacheAPC::getMem()->increaseCacheVersion('forum_topic_'.$this->id); 
       $this->main_parent->clearCategoryCache();
   }

   public function setCategory($category)
   {
        $this->path_0 = 0;
        $this->path_1 = 0;
        $this->path_2 = 0;
        $this->path_3 = 0;
        
        $this->main_category_id = $category->id;
                    
        foreach ($category->path_objects as $index => $object) {
             $this->{'path_'.$index} = $object->id;
        }
   }

   public static function isTopicOwner($id,$skipChecking = false)
   {
       $topic = self::fetch($id);
       
        if ($skipChecking==true) return $topic;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($topic->user_id == $currentUser->getUserID()) return $topic;
        
       return false;  
   }   
      
   public function __get($variable)
   {
       switch ($variable) {
              	
       case 'category':                      
           if ($this->path_3 != 0) {
               $this->category = $this->path_3 ;
           } elseif ( $this->path_2 != 0) {
               $this->category = $this->path_2;
           } elseif ($this->path_1 != 0) {
               $this->category = $this->path_1;
           } elseif ($this->path_0 != 0) {
               $this->category = $this->path_0;
           }
           
           return $this->category;
           
           break;
       	
       case 'main_parent':
                $this->main_parent = erLhcoreClassModelForumCategory::fetch($this->category);
                return $this->main_parent;
           break;
           
       	case 'parent_path':
       	        $categoryPath = array();
           	    erLhcoreClassModelForumCategory::getCategoryPath($categoryPath,$this->category); 
           	    $this->parent_path = $categoryPath;
           	    return $this->parent_path;
       	    break;
       			
       	case 'path':        	    
           	    $categoryPath = $this->parent_path;
           	    $categoryPath[] = array('title' =>$this->topic_name,'url' => $this->url_path);            	    
           	    $this->path = $categoryPath;         	    
           	    return $this->path;
       		break;
       		
       	case 'path_album':
           	    $categoryPath = $this->parent_path;           	   
           	    $categoryPath[] = array('title' =>$this->topic_name);  
           	    $this->path_album = $categoryPath;         	    
           	    return $this->path_album;
       		break;

       	case 'nice_path_base':
       	     $pathElements = array();
             foreach ($this->path_album as $item){
                $pathElements[] = erLhcoreClassCharTransform::TransformToURL($item['title']);
             }
             $this->nice_path_base = implode('/',$pathElements).'-'.$this->id.'ft.html';
             return $this->nice_path_base;
       	break;      
       		
        case 'url_path':        
                if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'nice_url_enabled' ) == true)    
                { 
                     $this->url_path = erLhcoreClassDesign::baseurl($this->nice_path_base,false);                     
                } else {
                    $this->url_path = erLhcoreClassDesign::baseurl('forum/topic').'/'.$this->id;
                    
                }   
                return $this->url_path;
            break;	
            
        case 'user':           
           try {
            $this->user = erLhcoreClassModelUser::fetch($this->user_id);
           } catch (Exception $e) {
               $this->user = false;
           }
           return $this->user;
           break;           
                                          
        case 'last_message':
                $this->last_message = false;
                
                $messageLast = erLhcoreClassModelForumMessage::getList(array('sort' => 'id DESC','filter' => array('topic_id' => $this->id),'limit' => 1));
                if (count($messageLast) > 0){
                    $this->last_message = current($messageLast);
                }
                
                return $this->last_message;
            break; 
                                  
        case 'last_message_username':  
                $this->last_message_username = false;                              
                if ($this->last_message !== false){
                    $this->last_message_username = $this->last_message->user->username;
                }
                return $this->last_message_username;
            break;
                                    
        case 'last_message_date':  
                $this->last_message_date = false;                              
                if ($this->last_message !== false){
                    $this->last_message_date = date('Y-m-d H:i',$this->last_message->ctime);
                }
                return $this->last_message_date;
            break;
                        
        case 'ctrim_frontend':
                $this->ctrim_frontend = date('Y-m-d',$this->ctime);
                return $this->ctrim_frontend;
            break;
        
        case 'url_path_base':
            if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'nice_url_enabled' ) == true)    
            { 
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect($this->nice_path_base);                     
            } else {
                $this->url_path_base = erLhcoreClassDesign::baseurldirect('forum/topic').'/'.$this->id;                
            }
            return $this->url_path_base;
            
            break;
             
       	default:
       		break;
       }
   }
            
   public static function getCount($params = array())
   {
       $session = erLhcoreClassForum::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_forum_topic" );   
         
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
   
   public function decCounter(){
       $this->message_count--;
       
       // Update last_message_ctime
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "ctime" )->from( "lh_forum_message" );
       $q->where( 
                     $q->expr->eq( 'topic_id', $q->bindValue($this->id ))   
          );
       $q->limit(1,0);
       $q->orderBy('id DESC');
      
       $stmt = $q->prepare();       
       $stmt->execute();   
       $result = $stmt->fetchColumn();   

       $this->last_message_ctime = (int)$result;              
       $this->saveThis();
       
       // We decrease message counter
       $this->main_parent->decCounterMessage($this->id);
   } 

   
   public function incCounter() {
       $this->message_count++;
       $this->last_message_ctime = time();
       $this->saveThis();

       // We increase message counter
       $this->main_parent->incCounterMessage($this->id);
           
   }
   
   public static function getList($paramsSearch = array())
   {
       $paramsDefault = array('limit' => 8, 'offset' => 0);
       
       $params = array_merge($paramsDefault,$paramsSearch);
       
       $session = erLhcoreClassForum::getSession('slave');
       $q = $session->createFindQuery( 'erLhcoreClassModelForumTopic' );  

       $conditions = array();

       $q2 = $q->subSelect();
       $q2->select( 'id' )->from( 'lh_forum_topic' );
       
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
      $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'last_message_ctime DESC, id DESC');
      
      $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_forum_topic.id', 'items.id' );
                
      // This is hack, because in general it's not neccesary, but I have no idea why mysql does not sort peroperly sometimes.
      // It should not cost a lot of performance, because we are sorting just small portion of returned record's
      $q->orderBy( isset($params['sort']) ? $params['sort'] : 'last_message_ctime DESC, id DESC' );          
            
      $objects = $session->find( $q );         
      return $objects; 
   }
   
   public $id = null;
   public $ctime = '';
   public $topic_name = '';
   public $path_1 = 0;
   public $main_category_id = 0;
   public $path_2 = 0;
   public $path_3 = 0;
   public $path_0 = 0;
   public $user_id = 0;
   public $message_count = 0;
   public $last_message_ctime = 0;
   public $topic_status = 0; // 0 - ACTIVE, 1 - LOCKED
    
   const ACTIVE = 0;   
   const LOCKED = 1;
     
}


?>