<?

class erLhcoreClassModelForumMessage {
        
    public function getState()
   {
       return array(
               'id'         => $this->id,
               'topic_id'   => $this->topic_id,             
               'ctime'      => $this->ctime, 
               'content'    => $this->content,             
               'user_id'    => $this->user_id,
               'ip'         => $this->ip,
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
        $topic = erLhcoreClassForum::getSession('slave')->load( 'erLhcoreClassModelForumMessage', (int)$aid );     
       return $topic;
   }
   
   public static function isMessageOwner($id,$skipChecking = false)
   {
       $msg = self::fetch($id);
       
        if ($skipChecking==true) return $msg;
       
       $currentUser = erLhcoreClassUser::instance();              
       if ($msg->user_id == $currentUser->getUserID()) return $msg;
        
       return false;  
   }
   
   public function __toString()
   {
       return $this->content_html;
   }
   
   public function removeThis()
   {          
       // We increase topic counter
       $this->topic->decCounter();
       
       foreach (erLhcoreClassModelForumFile::getList(array('filter' => array('message_id' => $this->id))) as $file)
       {
           $file->removeThis();         
       }
       
       erLhcoreClassForum::addToSphinx($this->id);       
       erLhcoreClassForum::getSession()->delete($this); 
       CSCacheAPC::getMem()->increaseCacheVersion('forum_user_version_'.$this->user_id);
   }

   public function saveThis()
   {    
       if ($this->id == null) {
           erLhcoreClassForum::getSession()->save($this);
           // We increase topic counter
           $this->topic->incCounter();
       } else {           
           erLhcoreClassForum::getSession()->update($this); 
       }
       
       $this->topic->chearTopicCache();
       
       CSCacheAPC::getMem()->increaseCacheVersion('forum_user_version_'.$this->user_id);
       erLhcoreClassForum::addToSphinx($this->id);        
   }

   public function __get($variable)
   {
       switch ($variable) {
       	       	
       case 'nice_path_base':
       	     $pathElements = array();
             foreach ($this->path as $item){
                $pathElements[] = urlencode(erLhcoreClassCharTransform::TransformToURL($item['title']));
             }
             $this->nice_path_base = implode('/',$pathElements).'-'.$this->pid.'fm.html';
             return $this->nice_path_base;             
       	break;
       	    
       	case 'url_path_base':
            if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'nice_url_enabled' ) == true) { 
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect($this->nice_path_base);
            } else {
                    $this->url_path_base = erLhcoreClassDesign::baseurldirect('forum/message').'/'.$this->id;
            } 
            return $this->url_path_base;           
            break;
            
       case 'path':

       	    $cache = CSCacheAPC::getMem();
       	    $cacheKey = md5($cache->getCacheVersion('forum_topic_'.$this->topic_id).'topic_path_'.$this->topic_id.'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);     
            if (($categoryPath = $cache->restore($cacheKey)) === false)
            {           	          	    
           	    $album = $this->topic;       	     
           	    $categoryPath = $album->path;
           	    $cache->store($cacheKey,$categoryPath);
            }                   	  
           	$categoryPath[] = array('title' => 'Message ID - '.$this->id);           	
           	$this->path = $categoryPath;
       	    return $this->path;
       		break;        		  	
           	
       	case 'url_path':         
            if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'nice_url_enabled' ) == true)    
            {                         
                    $this->url_path = erLhcoreClassDesign::baseurl($this->nice_path_base,false);            
                    return $this->url_path;
            } else {
                $this->url_path = erLhcoreClassDesign::baseurl('gallery/image').'/'.$this->pid;
                return $this->url_path;
            }               
            break;
            
            	
       case 'user':           
           try {
            $this->user = erLhcoreClassModelUser::fetch($this->user_id);
           } catch (Exception $e) {
               $this->user = false;
           }
           return $this->user;
           break;
           	
       case 'can_edit':         
           $currentUser = erLhcoreClassUser::instance();           
           $this->can_edit = $currentUser->getUserID() == $this->user_id && $currentUser->hasAccessTo('lhforum','edit_own_message');
                    
           return $this->can_edit;
           break;
           
       case 'topic':   
            $this->topic = erLhcoreClassModelForumTopic::fetch($this->topic_id,true);          
            return $this->topic;
           break;
           
       case 'user_message_count':           
           
           $cache = CSCacheAPC::getMem();
           $cacheKey = 'forum_user_message_count_'.$this->user_id.'_version_'.$cache->getCacheVersion('forum_user_version_'.$this->user_id);
           
           if (($this->user_message_count = $cache->restore($cacheKey)) === false) {
               $this->user_message_count = self::getCount(array('filter' => array('user_id' => $this->user_id)));
               $cache->store($cacheKey,$this->user_message_count);               
           }
           
           return $this->user_message_count;
           break;
           
       	case 'content_html':
           	    $this->content_html = erLhcoreClassBBCode::make_clickable(htmlspecialchars($this->content));           	   
           	    return $this->content_html;
       		break;
       		  
       	case 'content_plain_search':
           	    $this->content_plain_search = strip_tags(erLhcoreClassBBCode::make_clickable(htmlspecialchars($this->content)));              	    
           	    if (strlen($this->content_plain_search) > 300){
           	        $this->content_plain_search = mb_substr(0,300).'...';
           	    }           	            	   
           	    return $this->content_plain_search;
       		break;
       		
       	case 'ctime_front':
           	    $this->ctime_front = date('Y-m-d H:i',$this->ctime);           	   
           	    return $this->ctime_front;
       		break;	
       		
       	case 'ctime_front_full':
           	    $this->ctime_front = date('Y-m-d H:i:s',$this->ctime);           	   
           	    return $this->ctime_front;
       		break;       				
             
       	default:
       		break;
       }
   }

   public static function getCount($params = array())
   {              
       $session = erLhcoreClassGallery::getSession('slave');
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_forum_message" );     
       
       $conditions = array();
       
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
               $conditions[] = $q->expr->lt( $field,$q->bindValue($fieldValue ));
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue ));
           } 
      }
      
      if (count($conditions) > 0)
      {
          $q->where( 
                     $conditions   
          );
      } 
        
      if (isset($params['use_index'])) {         
          $q->useIndex( $params['use_index'] );
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
       $q = $session->createFindQuery( 'erLhcoreClassModelForumMessage' );  

       $conditions = array();

       $q2 = $q->subSelect();
       $q2->select( 'id' )->from( 'lh_forum_message' );
       
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
      $q2->orderBy(isset($params['sort']) ? $params['sort'] : 'id ASC');
      
      $q->innerJoin( $q->alias( $q2, 'items' ), 'lh_forum_message.id', 'items.id' );          
            
      $objects = $session->find( $q );         
      return $objects; 
   }
   
   public $id = null;
   public $topic_id = '';
   public $ctime = '';
   public $content = '';
   public $ip = '';
   public $user_id = 0;
}


?>