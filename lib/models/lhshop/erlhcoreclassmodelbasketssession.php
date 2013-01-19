<?php

class erLhcoreClassModelShopBasketSession {
	
   const COMPARE_COOKIE_VARIABLE = 'HPPG_Basket_images';
     
   public function getState()
   {
       return array(
               'id'             	=> $this->id,
               'user_id'   			=> $this->user_id,             
               'session_hash_crc32' => $this->session_hash_crc32,             
               'session_hash'       => $this->session_hash,             
               'mtime'        		=> $this->mtime
       );
   }
      
   public function __construct()
   {   			
	
   }
      
   public static function getInstance()  
   {
        if ( is_null( self::$instance ) )
        {          
        	if (!(!empty($_COOKIE[erLhcoreClassModelShopBasketSession::COMPARE_COOKIE_VARIABLE]) && self::$instance = erLhcoreClassModelShopBasketSession::getSessionParams($_COOKIE[erLhcoreClassModelShopBasketSession::COMPARE_COOKIE_VARIABLE])))		
			{
			    self::$instance = new erLhcoreClassModelShopBasketSession();
			    self::$instance->storeNewSession();
			}	
        }
        
        return self::$instance;
   }
    
   
   public function setState( array $properties )
   {
       foreach ( $properties as $key => $val )
       {
           $this->$key = $val;
       }
   }
   		
	// Pradedama nauja sesija 
	function storeNewSession()
	{	
		$RandomString = mt_rand().time();	
			
		$this->session_hash = sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$RandomString);	
		$this->session_hash_crc32 = abs(crc32(sha1($_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT'].$RandomString)));			
		$this->mtime = time()+2419200;		
				
		erLhcoreClassShop::getSession()->save($this);
								
		setcookie(erLhcoreClassModelShopBasketSession::COMPARE_COOKIE_VARIABLE,$this->session_hash,time()+2419200,'/');
					    	
	}
	
	public function clearFavoriteCache()
	{
		// Clear album cache
       $cache = CSCacheAPC::getMem(); 
       $cache->increaseCacheVersion('basket_'.$this->id);
	}
	
	public function __get($variable)
	{
		switch ($variable) {
			case 'order':
					$this->order = erLhcoreClassModelShopOrder::getInstance($this->id);
					return $this->order;
				break;
				
			case 'basket_items':			     			     
			        $cache = CSCacheAPC::getMem();
					$this->basket_items = erLhcoreClassModelShopBasketImage::getImages(array('cache_key' => 'version_'.$cache->getCacheVersion('basket_'.$this->id),'filter' => array('session_id' => $this->id)));
					return $this->basket_items;
				break;
		
			case 'account_credits':
					$currentUser = erLhcoreClassUser::instance();
			   		$credits = erLhcoreClassModelShopUserCredit::fetch($currentUser->getUserID());			   		
					$this->account_credits = $credits->credits;
					return $this->account_credits;
				break;
				
			default:
				break;
		}
	}
	
	// Fills atributes if record exists
	public static function getSessionParams($SHA)
	{		
		if (strlen($_COOKIE[erLhcoreClassModelShopBasketSession::COMPARE_COOKIE_VARIABLE]) != 40) return false;		
		
		$session = erLhcoreClassShop::getSession('slave');
       	$q = $session->createFindQuery( 'erLhcoreClassModelShopBasketSession' ); 
       	
       	$currentUser = erLhcoreClassUser::instance();
		if ($currentUser->isLogged())
		   $user_id = $currentUser->getUserID();	
		else 
		    $user_id = 0;	
        
		if ($user_id > 0){      	
	       	$q->where( $q->expr->lOr( $q->expr->eq( 'user_id', $q->bindValue( $user_id ) ),       	
	       								$q->expr->lAnd(
	       									$q->expr->eq( 'session_hash_crc32', $q->bindValue( abs(crc32($SHA)) ) ),
	       									$q->expr->eq( 'session_hash', $q->bindValue( $SHA ) )
	       								)                                      
	                                       ) );
		} else {
			$q->where(  $q->expr->eq( 'session_hash_crc32', $q->bindValue( abs(crc32($SHA)) ) ),
						$q->expr->eq( 'session_hash', $q->bindValue( $SHA ) )
					                                      
                    );
		}

       	$q->limit(1,0);
             	
       	$objects = $session->find( $q );
     
		if (count($objects) > 0)
		{			
			foreach ($objects as $favouriteSession)
			{				
				$favouriteSession->mtime = time();
				// For timestamp
                erLhcoreClassShop::getSession()->update($favouriteSession);
						
				setcookie(erLhcoreClassModelShopBasketSession::COMPARE_COOKIE_VARIABLE,$favouriteSession->session_hash,time()+2419200,'/');	
			}	
			
			return 	$favouriteSession;	
		}
		return false;	
	}
	
	// Called after order
   public function removeThis()
   {
   		foreach ($this->basket_items as $item)
   		{
   			$item->removeThis();
   		}  
   		
   		erLhcoreClassShop::getSession()->delete($this);
   		$this->clearFavoriteCache();  		 	
   }
    
   public $id = null;
   public $user_id = 0;
   public $session_hash = '';  
   public $session_hash_crc32 = '';  
   public $mtime = '';
   
   private static $instance = null; 

}


?>