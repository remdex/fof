<?php

class erLhcoreClassUser{
    
    static function instance()
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erLhcoreClassUser();            
        }         
        return self::$instance;    
    }    
    
   function __construct()
   {
       $options = new ezcAuthenticationSessionOptions();
       $options->validity = 3600;
 
       $this->session = new ezcAuthenticationSession($options);
       $this->session->start(); 
                     
       $this->credentials = new ezcAuthenticationPasswordCredentials( $this->session->load(), null );        
                        
       if ( !$this->session->isValid( $this->credentials ) )
       {
           $this->authenticated = false;   
           
           if ( isset($_SESSION['user_id']) )
           {
               unset($_SESSION['user_id']);
               unset($_SESSION['access_array']);
               unset($_SESSION['access_timestamp']);
           }           
               
       } else {
          $this->session->save( $this->session->load() );        
          $this->userid = $_SESSION['user_id'];
          $this->authenticated = true;
       } 
   }
      
   function setLoggedUserInstantly($user_id)
   {
       $this->session->destroy();
       
       $cfgSite = erConfigClassLhConfig::getInstance();
	   $secretHash = $cfgSite->getSetting( 'site', 'secrethash' );
    
       $this->credentials = new ezcAuthenticationIdCredentials( $user_id );       
       $this->authentication = new ezcAuthentication( $this->credentials );  
            
       $database = new ezcAuthenticationDatabaseInfo( ezcDbInstance::get(), 'lh_users', array( 'id', 'password' ) );       
       $this->filter = new ezcAuthenticationDatabaseCredentialFilter( $database );
       $this->filter->registerFetchData(array('id','username','email'));              
       $this->authentication->addFilter( $this->filter ); 
              
       $this->authentication->session = $this->session;
                   
       if ( !$this->authentication->run() )
       {  
            return false;
            // build an error message based on $status
       }
       else
       {       
           $data = $this->filter->fetchData();         
           // Anonymous user does not have access to login
           if (erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'anonymous_user_id' ) != $data['id'][0])
           {                 
                $_SESSION['user_id'] = $data['id'][0];
                $this->userid = $data['id'][0];                        
                $this->authenticated = true;                
                unset($_SESSION['access_array']);                
                return true;
           }
           
           return false;
       }
   }
   
   function authenticate($username,$password)
   {
       $this->session->destroy();
       
       $cfgSite = erConfigClassLhConfig::getInstance();
	   $secretHash = $cfgSite->getSetting( 'site', 'secrethash' );
    	   	   
       $this->credentials = new ezcAuthenticationPasswordCredentials( $username, sha1($password.$secretHash.sha1($password)) );
       $database = new ezcAuthenticationDatabaseInfo( ezcDbInstance::get(), 'lh_users', array( 'email', 'password' ) );
       $this->authentication = new ezcAuthentication( $this->credentials );       
       
       $this->filter = new ezcAuthenticationDatabaseFilter( $database );
       $this->filter->registerFetchData(array('id','username','email','disabled'));
              
       $this->authentication->addFilter( $this->filter );       
       $this->authentication->session = $this->session;
       
       if ( !$this->authentication->run() )
       {          
           
            return false;
            // build an error message based on $status
       }
       else
       {       
           // Anonymous user does not have access to login
           $data = $this->filter->fetchData();
           if (erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'anonymous_user_id' ) != $data['id'][0])
           {
	           	if ($data['disabled'][0] == 0) {
	           		
	                $_SESSION['user_id'] = $data['id'][0];
	                $this->userid = $data['id'][0];                        
	                $this->authenticated = true;                
	                unset($_SESSION['access_array']);
	                	                
	                return true;
	           	}
           }
           
           return false;
       }
   }
   
   function getStatus()
   {
       return $this->authentication->getStatus();
   }
   
   function isLogged()
   {
       return $this->authenticated;
   }
   
   function setLoggedUser($user_id)
   {
       if ($user_id != $this->userid) {
           if (isset($_SESSION['access_array'])) unset($_SESSION['access_array']);
           $this->AccessArray = false;
           $this->userid = $user_id;
           $this->authenticated = true;
       }
   }
   
   function logout()
   {
       unset($_SESSION['access_array']);
       unset($_SESSION['access_timestamp']);
       unset($_SESSION['user_id']);
       $this->session->destroy();
   }
         
   public static function getSession($type = false)
   {                
        if ($type === false && !isset( self::$persistentSession ) )
        {
            self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get(),
                new ezcPersistentCodeManager( './pos/lhuser' )
            );
        } elseif ($type !== false && !isset( self::$persistentSessionSlave ) ) {            
            self::$persistentSessionSlave = new ezcPersistentSession(
                ezcDbInstance::get($type),
                new ezcPersistentCodeManager( './pos/lhuser' )
            );
        }
        
        return $type === false ? self::$persistentSession : self::$persistentSessionSlave;
        
   }
   
   function getUserData($canUseCache = false)
   {
      if ($canUseCache == true && isset($GLOBALS['CacheUserData_'.$this->userid])) return $GLOBALS['CacheUserData_'.$this->userid];
            
      $GLOBALS['CacheUserData_'.$this->userid] = erLhcoreClassUser::getSession()->load( 'erLhcoreClassModelUser', $this->userid );
            
      return $GLOBALS['CacheUserData_'.$this->userid];
   }
   
   function getUserID()
   {
       return $this->userid;
   }
         
   function getUserList()
   {
     $db = ezcDbInstance::get();
                 
     $stmt = $db->prepare('SELECT * FROM lh_users ORDER BY id ASC');           
     $stmt->execute();
     $rows = $stmt->fetchAll();
            
     return $rows;
   }
   
   function hasAccessTo($module, $functions)
   {
       $AccessArray = $this->accessArray();
       
       // Global rights
       if (isset($AccessArray['*']['*']) || isset($AccessArray[$module]['*']))
       {
           return true;
       }
          
       // Provided rights have to be set
       if (is_array($functions))
       {
           foreach ($functions as $function)
           {
               // Missing one of provided right
               if (!isset($AccessArray[$module][$function])) return false;
           }           
       } else {
           if (!isset($AccessArray[$module][$functions])) return false;
       }   
       
       return true;    
   }
         
   public static function getUserCount($params = array())
   {
       $session = erLhcoreClassGallery::getSession();
       $q = $session->database->createSelectQuery();  
       $q->select( "COUNT(*)" )->from( "lh_users" );     
         
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
               $conditions[] = $q->expr->lt( $field, $fieldValue );
           } 
      }
      
      if (isset($params['filtergt']) && count($params['filtergt']) > 0)
       {
           foreach ($params['filtergt'] as $field => $fieldValue)
           {
               $conditions[] = $q->expr->gt( $field, $fieldValue );
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
      
      return $stmt->fetchColumn(); 
   }
   
   function accessArray()
   {
           
       if ($this->AccessArray !== false) return $this->AccessArray;

       if (isset($_SESSION['access_array'])) {
                    
           $this->AccessArray = $_SESSION['access_array'];
           $this->AccessTimestamp =  $_SESSION['access_timestamp'];
                   
           $cacheObj = CSCacheAPC::getMem();
           
           if (($AccessTimestamp = $cacheObj->restore('cachetimestamp_accessfile_version_'.$cacheObj->getCacheVersion('site_version'))) === false)
           {          
               $cfg = erConfigClassLhCacheConfig::getInstance();  
               $AccessTimestamp = $cfg->getSetting( 'cachetimestamps', 'accessfile' );
               $cacheObj->store('cachetimestamp_accessfile_version_'.$cacheObj->getCacheVersion('site_version'),$AccessTimestamp);
           }
           
           if ( $this->AccessTimestamp === $AccessTimestamp)
           {               
               return $this->AccessArray;
           }
       }
       
   
       
       if ($this->cacheCreated == false) {
           $this->cacheCreated = true;
           ezcCacheManager::createCache( 'userinfo', 'cache/userinfo', 'ezcCacheStorageFileArray', array('ttl'   => 60*60*24*1 ) ); 
       }
       
       $cache = ezcCacheManager::getCache( 'userinfo' );

       $id = $this->userid;
       
       $cfg = erConfigClassLhCacheConfig::getInstance();
              
       $AccessTimestamp = $cfg->getSetting( 'cachetimestamps', 'accessfile' );
       $CheckExpire = false;
           
       if ( ( $data = $cache->restore( $id ) ) === false || $AccessTimestamp < time() )       
       {       
            $this->AccessArray = $this->generateAccessArray();
            
            $data['access_array'] = $this->AccessArray;
            $data['access_timestamp'] = $AccessTimestamp;            
            $this->AccessTimestamp = $AccessTimestamp;
                                    
            if ($AccessTimestamp < time() )
            {
                $AccessTimestamp = time() + 60*60*24*1;                
                $cfg->setSetting( 'cachetimestamps', 'accessfile', $AccessTimestamp );
                $cfg->save();
                $data['access_timestamp'] = $AccessTimestamp;
                $this->AccessTimestamp = $AccessTimestamp;
            }

            // Do not store empty access_array
            if ( !empty($data['access_array']) ) {
                $cache->store( $id, $data );
            }
                        
            $_SESSION['access_array'] = $this->AccessArray;
                        
            
       } else {
           $CheckExpire = true;
           $this->AccessArray = $data['access_array'];
           $this->AccessTimestamp = $data['access_timestamp'];
       }
              
       if ( $CheckExpire === true && $data['access_timestamp'] != $AccessTimestamp)
       {
           $this->AccessArray = $this->generateAccessArray();
           $this->AccessTimestamp = $AccessTimestamp;
           $data['access_timestamp'] = $AccessTimestamp;
           $data['access_array'] = $this->AccessArray;
           
           if ( !empty($data['access_array']) ) {      
               $cache->store( $id, $data );  
           }
       }
          
       $_SESSION['access_array'] = $this->AccessArray;
       $_SESSION['access_timestamp'] = $this->AccessTimestamp;
              
       return $this->AccessArray;
   }
   
   function generateAccessArray()
   {
       if ($this->userid !== null) {
            $UserIDGenerate = $this->userid;
       } else {
            $UserIDGenerate = erConfigClassLhConfig::getInstance()->getSetting( 'user_settings', 'anonymous_user_id' );
       }
       
       $accessArray = erLhcoreClassRole::accessArrayByUserID( $UserIDGenerate );
       
       
       return $accessArray;
   }
    
   private static $persistentSession;
   
   // For selects
   private static $persistentSessionSlave;
   
   private static $instance = null; 
   
   private $userid;   
   private $AccessArray = false;
   private $AccessTimestamp = false;
   private $cacheCreated = false;
   
   // Authentification things
   public $authentication;
   public $session;
   public $credentials;
   public $authenticated;
   public $status;
   public $filter;

}


?>