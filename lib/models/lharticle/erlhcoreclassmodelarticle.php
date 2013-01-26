<?php

class erLhcoreClassModelArticle {
        
	public function getState()
	{
		return array(
			'id'                     => $this->id,
			'article_name'           => $this->article_name,
			'intro'                  => $this->intro,
			'body'                   => $this->body,
			'publishtime'            => $this->publishtime,
			'descriptionoveride'     => $this->descriptionoveride,
			'category_id'            => $this->category_id,
			'has_photo'              => $this->has_photo,               
			'file_name'              => $this->file_name,
			'pos'                    => $this->pos,
			'alias_url'              => $this->alias_url,
			'alternative_url'        => $this->alternative_url,
			'is_modal'               => $this->is_modal,
			'mtime'                  => $this->mtime,
		);
	}
   
	public function setState( array $properties )
	{
		foreach ( $properties as $key => $val )
		{
			$this->$key = $val;
		}
	} 
       
	public function saveThis() {

	    $this->mtime = time();
	    
   		erLhcoreClassArticle::getSession()->saveOrUpdate( $this );
   		   		   		
   		$cache = CSCacheAPC::getMem();
   		$cacheVersion = $cache->increaseCacheVersion('article_cache_version');
   }
   
   public static function getCount($params = array())
   {
       $session = erLhcoreClassAbstract::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(id)" )->from( "lh_article" );   
         
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
       
       if (!isset($params['disable_sql_cache']))
       {
	       	$sql = CSCacheAPC::multi_implode(',',$params);
	       	 
	       	$cache = CSCacheAPC::getMem();
	       	$cacheKey = isset($params['cache_key']) ? md5($sql.$params['cache_key']) : md5('site_version_article_list_'.$cache->getCacheVersion('article_cache_version').$sql);
	       
	       	if (($result = $cache->restore($cacheKey)) !== false)
	       	{
	       		return $result;	       
	       	}
       }
       
       $session = erLhcoreClassArticle::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelArticle' );  
       
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
               $conditions[] = $q->expr->gt( $field, $q->bindValue($fieldValue));
           } 
       }      
      
       if (count($conditions) > 0)
       {
          $q->where( 
                     $conditions   
          );
       } 
      
      $q->limit($params['limit'],$params['offset']);
                
      $q->orderBy(isset($params['sort']) ? $params['sort'] : 'pos ASC, id DESC' ); 
            
      $objects = $session->find( $q ); 
         
      if (!isset($params['disable_sql_cache']))
      {
      		$cache->store($cacheKey,$objects);
      }      

      return $objects; 
   }
   
   
	public function __get($var)
	{
		switch ($var) {
			case 'url_article':
				   if ($this->alternative_url != '') {
				   		$this->url_article = $this->alternative_url;
				   } elseif ($this->alias_url != '') {
				   		$this->url_article = $this->alias_url;
				   } else {
				   		$this->url_article = erLhcoreClassDesign::baseurl(urlencode(erLhcoreClassCharTransform::TransformToURL($this->article_name).'-'.$this->id.'a.html'),false);
				   }
				   return $this->url_article;
				break;
				
			case 'mtime_front':
   			      return date('Y-m-d H:i:s',$this->mtime);
   			    break;
   			    
   			case 'ptime_front':
   			      return date('Y-m-d H:i:s',$this->publishtime);
   			    break;
   			    
   			case 'category':
				   $this->category = erLhcoreClassModelArticleCategory::fetch( $this->category_id );
				   return $this->category;
				break;
				        	
			case 'photo_path':
				   $photo_path = 'var/media/'.$this->id.'/images/'.$this->file_name;
				   if (file_exists($photo_path))
				       return $photo_path;    
				          		       
				   return false;
				break;
		
			case 'thumb_article':        	       	           	        
		   	    if (!file_exists('var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg') ) { 
		   	        try { 
		   	          erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbarticle', $this->photo_path,'var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg' ); 
		   	        } catch (Exception $e) {
		   	            print_r($e);
		   	        }
		   	    }
		   	    $this->thumb_article = '/var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg';  
		   	    return $this->thumb_article;	    
			    break;
			    
			case 'thumbsmall_article':        	       	           	        
		   	    if (!file_exists('var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg')) {  
		   	        erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbsmall_article', $this->photo_path,'var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg' ); 
		   	    }
		   	    $this->thumbsmall_article = '/var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg';  
		   	    return $this->thumbsmall_article;	    
			    break;
				
			default:
				break;
		}
	}
   
	public function __toString(){
		return $this->article_name;
	}
   
	public function removePhotos()
	{
		if (file_exists($this->photo_path)){
		   unlink($this->photo_path);
		}
		
		if (file_exists('var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg')){
		   unlink('var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg');
		}
		
		if (file_exists('var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg')){
		   unlink('var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg');
		}
	}
   
	public function removePhoto()
	{
	   if ($this->photo_path !== false){
	       unlink($this->photo_path);
	       if (file_exists('var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg')) { 
	            unlink('var/media/'.$this->id.'/images/'.$this->id.'_thumb.jpg');
	       }
	       
	       if (file_exists('var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg')) { 
	            unlink('var/media/'.$this->id.'/images/'.$this->id.'_thumbsmall.jpg');
	       }
	       $this->has_photo = 0;
	       $this->file_name = '';
	   }
	}
   
	public function removeThis()
	{
	   $this->removePhoto();
	   $session = erLhcoreClassArticle::getSession();
	   $session->delete($this);
	}
   
	public static function getArticlesBySearchCount($field,$value)
	{
		$db = ezcDbInstance::get();
		$stmt = $db->prepare( "SELECT count(*) AS total FROM lh_article WHERE $field = :category_parent" );   
		$stmt->bindValue( ':category_parent',$value);       
		$stmt->execute();
		$rows = $stmt->fetchAll(); 
		return $rows[0]['total'];
	}
    
    public $id = null;
    public $article_name = '';
    public $intro = '';
    public $body = '';
    public $publishtime = '';
    public $descriptionoveride = '';
    public $category_id = '';
    public $has_photo = 0;
    public $file_name = '';
    public $pos = 0;
    public $alias_url = '';
    public $alternative_url = '';
    public $is_modal = 0;
    public $mtime = 0;

}
?>