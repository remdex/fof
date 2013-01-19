<?php

class erLhcoreClassModelUserOauth {
        
	public function getState() {
		
		return array(
			'id'     				=> $this->id,
			'user_id'     			=> $this->user_id,
			'twitter_user_id'     	=> $this->twitter_user_id,
			'user_name'     		=> $this->user_name,
			'oauth_token'     		=> $this->oauth_token,
			'oauth_token_secret'    => $this->oauth_token_secret,
		);
		
	}
   
	public function setState( array $properties ) {
		
		foreach ( $properties as $key => $val ) {
			$this->$key = $val;
		}
		
	}

	public static function fetch($id) {
		
		$data = erLhcoreClassUser::getSession('slave')->load( 'erLhcoreClassModelUserOauth', (int)$id );
		
		return $data;
		
	}
	
	public function saveThis() {
	
		erLhcoreClassUser::getSession()->saveOrUpdate($this);      	
	
	}
	
	public function updateThis() {   	   		        
		
		erLhcoreClassUser::getSession()->update($this);   	
	} 
	
	public function removeThis() {	
		
		erLhcoreClassUser::getSession()->delete($this);
		
	}
	
	public static function getCount($params = array()) {
		
		$session = erLhcoreClassUser::getSession('slave');
		
		$q = $session->database->createSelectQuery(); 
		 
		$q->select( "COUNT(lh_user_oauth.id)" )->from( "lh_user_oauth" );  
		 
		$conditions = array();
				
		if (isset($params['filter']) && count($params['filter']) > 0)
		{                     
		   foreach ($params['filter'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
		   }
		} 
		
		if (isset($params['filterne']) && count($params['filterne']) > 0)
		{                     
		   foreach ($params['filterne'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->neq( $field, $q->bindValue($fieldValue) );
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
		
		if (isset($params['filterlte']) && count($params['filterlte']) > 0)
		{
		   foreach ($params['filterlte'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
		   }
		}
		
		if (isset($params['filtergte']) && count($params['filtergte']) > 0)
		{
		   foreach ($params['filtergte'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
		   }
		}
		
		if (count($conditions) > 0) {
			$q->where ( $conditions );
		}    
		     
		$stmt = $q->prepare();  
		     
		$stmt->execute();  
		
		$result = $stmt->fetchColumn(); 
		    
		return $result; 
	
	}

	public static function getList($paramsSearch = array()) {       
		      
		$paramsDefault = array('limit' => 25, 'offset' => 0);
		
		$params = array_merge($paramsDefault,$paramsSearch);
		
		$session = erLhcoreClassUser::getSession();
		
		$q = $session->createFindQuery( 'erLhcoreClassModelUserOauth' );  
		
		$conditions = array(); 
		         
		if (isset($params['filter']) && count($params['filter']) > 0)
		{                     
		   foreach ($params['filter'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->eq( $field, $q->bindValue($fieldValue) );
		   }
		} 
		
		if (isset($params['filterne']) && count($params['filterne']) > 0)
		{                     
		   foreach ($params['filterne'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->neq( $field, $q->bindValue($fieldValue) );
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
		
		if (isset($params['filterlte']) && count($params['filterlte']) > 0)
		{
		   foreach ($params['filterlte'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->lte( $field, $q->bindValue($fieldValue) );
		   }
		}
		
		if (isset($params['filtergte']) && count($params['filtergte']) > 0)
		{
		   foreach ($params['filtergte'] as $field => $fieldValue)
		   {
		       $conditions[] = $q->expr->gte( $field, $q->bindValue($fieldValue) );
		   }
		}
		
		if (count($conditions) > 0) {
		  $q->where ( $conditions );
		}
		
		$q->limit($params['limit'],$params['offset']);
		
		$q->orderBy(isset($params['sort']) ? $params['sort'] : 'id DESC' ); 
		
		$objects = $session->find( $q );
		
		return $objects; 
		
	}
    
	public static function findOne($paramsSearch) {
		
	   $list = self::getList($paramsSearch);   
	       
	   return current($list);
	   
	}	
	
	public static function isTwitterLoginOwner($id, $skipChecking = false) {
		
		$twitterLogin = self::fetch($id);
       
        if ( $skipChecking == true ) return $twitterLogin;
       
		$currentUser = erLhcoreClassUser::instance(); 
		             
		if ($twitterLogin->user_id == $currentUser->getUserID()) return $twitterLogin;
		
		return false;
		
	}
	
	public $id = null;
	public $user_id = 0;
	public $twitter_user_id = 0;
	public $user_name = '';
	public $oauth_token = '';
	public $oauth_token_secret = '';

}

?>