<?php

class erLhcoreClassForum {
        
   function __construct()
   {
 
   }

   public static function getSession($type = false)
   {
        if ($type === false && !isset( self::$persistentSession ) )
        {
            self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get(),
                new ezcPersistentCodeManager( './pos/lhforum' )
            );
        } elseif ($type !== false && !isset( self::$persistentSessionSlave ) ) {            
            self::$persistentSessionSlave = new ezcPersistentSession(
                ezcDbInstance::get($type),
                new ezcPersistentCodeManager( './pos/lhforum' )
            );
        }

        return $type === false ? self::$persistentSession : self::$persistentSessionSlave;
   }
   
   public static function addToSphinx($msg_id) {
       if (erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled' ) == true)
       {
           $db = ezcDbInstance::get();
           $stmt = $db->prepare('REPLACE INTO `lh_forum_message_delta` (`id`) VALUES (:id)');
           $stmt->bindValue( ':id',$msg_id);
           $stmt->execute();
       }
   }
   
   public static function searchSphinx($params = array('SearchLimit' => 20),$cacheEnabled = true)  
   {
      if ($cacheEnabled == true ) {
        $cache = CSCacheAPC::getMem();        
        $cacheKey =  md5('SphinxSearchForum_VersionCache'.$cache->getCacheVersion('sphinx_forum_cache_version').erLhcoreClassGallery::multi_implode(',',$params));
      }
      
      if ($cacheEnabled == false || ($resultReturn = $cache->restore($cacheKey)) === false)
      {
      
      $cl = erLhcoreClassGallery::getSphinxInstance();
      $cl->ResetFilters();
      $cl->SetSelect('');
      $maxMatches = erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'max_matches' );    
                  
      $cl->SetLimits(isset($params['SearchOffset']) ? (int)$params['SearchOffset'] : 0,(int)$params['SearchLimit'],$maxMatches);
                    
      $filter = isset($params['Filter']) ? $params['Filter'] : array();  
       
      foreach ($filter as $field => $value)  
      {                     
          if ( is_numeric( $value ) and $value > 0 )
          {
          	 $cl->SetFilter( $field, array((int)$value));
          }
          else if ( is_array( $value ) and count( $value ) )
          {           
             $cl->SetFilter( $field, $value);
          }       
      }
      
      if (isset($params['filtergt'])) {
           foreach ($params['filtergt'] as $attribute => $fieldValue) { 
               $cl->SetFilterRange( $attribute, (int)0, (int)$fieldValue, true );
           }
      }  
      
      if (isset($params['filterlt'])) {
           foreach ($params['filterlt'] as $attribute => $fieldValue) {          
               $cl->SetFilterRange( $attribute, (int)0, (int)$fieldValue, false );
           }
      } 
      
      if (isset($params['filterfloatgt'])) {
           foreach ($params['filterfloatgt'] as $attribute => $fieldValue) { 
               $cl->SetFilterFloatRange( $attribute, (float)0, (float)$fieldValue, true );
           }
      }  
      
      if (isset($params['filterfloatlt'])) {
           foreach ($params['filterfloatlt'] as $attribute => $fieldValue) {          
               $cl->SetFilterFloatRange( $attribute, (float)0, (float)$fieldValue, false );
           }
      } 

      if (isset($params['FilterFloat'])) {
            foreach ($params['FilterFloat'] as $attribute => $fieldValue) {          
               $cl->SetFilterFloatRange( $attribute, (float)$fieldValue, (float)$fieldValue, false );
           }
      }
                  
      if (isset($params['custom_filter'])){
        $cl->SetSelect ( $params['custom_filter']['filter'] );
        $cl->SetFilter ( $params['custom_filter']['filter_name'], array(1) );
      }
      
      $cl->SetSortMode(SPH_SORT_EXTENDED, isset($params['sort']) ? $params['sort'] : '@id DESC');

      $startAppend = erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled_wildcard') == true ? '*' : '';
      
      $cl->setGroupBy('topic_id', SPH_GROUPBY_ATTR);
      
      $weights = array (
        'topic_name' => 10,
        'message' => 9
      );     
              
      // Make some weightning
      $cl->SetFieldWeights($weights);
         
      $result = $cl->Query( (isset($params['keyword']) && trim($params['keyword']) != '') ? trim($params['keyword']).$startAppend : '', erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'index_forum' ) );
     
      
      if ($result['total_found'] == 0 || !isset($result['matches'])){
      
          if (isset($params['relevance'])) { 
              return 1;  
          } else {
            return array('total_found' => 0,'list' => null);
          }      
      }
      
      $idMatch = array();
        
      if (isset($params['relevance'])) {          
          $itemCurrent = array_shift($result['matches']);                   
          $relevanceValue = $itemCurrent['weight'];         

          if ($cacheEnabled == true ) {
            $cache->store($cacheKey,$relevanceValue,12000);
          }
          return $relevanceValue;
      }
      
      foreach ($result['matches'] as $key => $match)
      {
         $idMatch[$key] = null;
      }
            
	  if (count($idMatch) == 0)
          	return array('total_found' => 0,'list' => null);   
        
      $listObjects = erLhcoreClassModelForumMessage::getList(array('limit' => (int)$params['SearchLimit'],'filterin'=> array('id' => array_keys($idMatch))));
      
      foreach ($listObjects as $object)
      {     
          $idMatch[$object->id] = $object;
      }     
       
      if ($result['total_found'] > $maxMatches) {
          $result['total_found'] = $maxMatches;
      }
      
      $resultReturn = array('total_found' => $result['total_found'],'list' => $idMatch);
      
      if ($cacheEnabled == true) {
            $cache->store($cacheKey,$resultReturn,12000);
      } 
        
      }
      
      return $resultReturn;
       
   }
   
   // For all other
   private static $persistentSession;
   
   // For selects
   private static $persistentSessionSlave;
}