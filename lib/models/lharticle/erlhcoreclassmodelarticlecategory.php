<?
/**
 * Msg
 * Status - 
 * 0 - Pending delivery
 * 1 - Delivered
 * 
 * user_id - administartor user_id,
 * If 0 web user
 * 
 * */

class erLhcoreClassModelArticleCategory {
        
   public function getState()
   {
       return array (
               'id'                 => $this->id,
               'category_name'      => $this->category_name,         
               'intro'              => $this->intro,           
               'parent_id'          => $this->parent_id,
               'pos'                => $this->pos,
               'hide_articles'      => $this->hide_articles,
      );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
      
   public static function fetch($categoryID)
   {
       $Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', $categoryID);  
       return $Category;       
   }
   
   public function __get($var)
   {
       switch ($var) {
       	case 'url_path':
       		   $this->url_path = erLhcoreClassDesign::baseurl(urlencode(erLhcoreClassCharTransform::TransformToURL($this->category_name).'-'.$this->id.'c.html'),false);
       		   return $this->url_path;
       		break;
       		
       	case 'parent':
       		   $this->parent = false;
       	       if ( $this->parent_id > 0 ){
       	           try {
       	            $this->parent = self::fetch($this->parent_id);
       	           } catch (Exception $e) {
       	               
       	           }
       	       }       	       
       		   return $this->parent;
       		break;
       
       	default:
       		break;
       }       
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
       $q = $session->createFindQuery( 'erLhcoreClassModelArticleCategory' );  
       
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
   
   public function removeThis()
   {       
       foreach (self::getList(array('limit' => 1000000,'filter' => array('parent_id' => $this->id))) as $category) {
           $category->removeThis();
       }
       
       foreach (erLhcoreClassModelArticle::getList(array('limit' => 1000000,'filter' => array('category_id' => $this->id))) as $article) {
           $article->removeThis();
       }      
       
       $session = erLhcoreClassArticle::getSession();
       $session->delete($this);
   }
   
   public function saveThis() {
   		// Clean cache because templates get's compiled
   		erLhcoreClassArticle::getSession()->saveOrUpdate( $this );
   		   		   		
   		$cache = CSCacheAPC::getMem();
   		$cacheVersion = $cache->increaseCacheVersion('article_cache_version');
   }
   
   public $id = null;
   public $category_name = '';  
   public $parent_id = 0;
   public $intro = '';
   public $pos = 0;
   public $hide_articles = 0;
}

?>