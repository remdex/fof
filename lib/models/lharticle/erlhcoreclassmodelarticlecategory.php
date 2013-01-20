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
               'placement'          => $this->placement,          
               'descriptionoveride' => $this->descriptionoveride,
               'intro'              => $this->intro,           
               'parent'             => $this->parent
      );
   }
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
      
   public static function getCategoryByID($categoryID)
   {
       $Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', $categoryID);  
       return $Category;
       
   }
   
   public function __get($var)
   {
       switch ($var) {
       	case 'url_path':
       		   $this->url_path = erLhcoreClassDesign::baseurl(urlencode(erLhcoreClassCharTransform::TransformToURL($this->category_name).'-'.$this->id.'cat.html'));
       		   return $this->url_path;
       		break;
       
       	default:
       		break;
       }       
   }
   
   public function removeThis()
   {
       $articles = erLhcoreClassModelArticle::getArticlesByCategory($this->id,1000);
       foreach ($articles as $article)
       {
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
   
   
   public function getParentCategories($parent = false)
   {
       $session = erLhcoreClassArticle::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelArticleCategory' );  
       
       $q->where( 
        $q->expr->eq( 'parent', $q->bindValue($parent === false ? $this->id : $this->parent) )        
        );
              
      $objects = $session->findIterator( $q, 'erLhcoreClassModelArticleCategory' );         
      return $objects; 
   }
   
   public static function getTopLevelCategories()
   {
       $session = erLhcoreClassArticle::getSession();
       $q = $session->createFindQuery( 'erLhcoreClassModelArticleCategory' );         
       $q->where(           
        $q->expr->eq( 'parent', $q->bindValue( 0 ) )        
        );              
      $objects = $session->findIterator( $q, 'erLhcoreClassModelArticleCategory' );         
      return $objects; 
   }
   
   public $id = null;
   public $category_name = '';  
   public $placement = '';   
   public $descriptionoveride = '';
   public $parent = 0;
   public $intro = '';
}

?>