<?php

mb_internal_encoding('utf-8');

class CSCacheAPC {

    static private $m_objMem = NULL;
    public $cacheEngine = null;
    public $cacheGlobalKey = null;

    public $cacheKeys = array(
    'last_hits_version',        // Last visited pages
    'most_popular_version',     // Most popular images, watched times
    'top_rated',                // Top rated images
    'last_uploads',             // Last uploaded images
    'last_commented',           // Last commented images
    'last_rated',               // Last rated images
    'ratedrecent_version',      // Recently rated images
    'site_version',             // Global site version
    'color_images',             // Global color pages version
    'album_version',            // Albums version key
    'sphinx_cache_version',     // Sphinx search cache version
    );
    
    public function increaseImageManipulationCache()
    {
        $this->increaseCacheVersion('last_hits_version',time(),600);
        $this->increaseCacheVersion('most_popular_version',time(),1500);
        $this->increaseCacheVersion('last_uploads');
        $this->increaseCacheVersion('top_rated');
        $this->increaseCacheVersion('last_commented');        
        $this->increaseCacheVersion('last_rated');        
        $this->increaseCacheVersion('ratedrecent_version'); 
        $this->increaseCacheVersion('sphinx_cache_version');        
        $this->increaseCacheVersion('site_version');  
        $this->increaseCacheVersion('color_images');  
        $this->increaseCacheVersion('album_version');  
        $this->increaseCacheVersion('popularrecent_version',time(),600);          
    }
    
    function setSession($identifier,$value)
    {
    	$_SESSION[$identifier] = $value;
    }
    
    function getSession($identifier) {
    
    	if (isset($_SESSION[$identifier])){
    		return $_SESSION[$identifier];
    	}
    
    	return false;
    }
    
    function __construct() {  
              
        $cacheEngineClassName = erConfigClassLhConfig::getInstance()->getSetting( 'cacheEngine', 'className' );        
        $this->cacheGlobalKey = erConfigClassLhConfig::getInstance()->getSetting( 'cacheEngine', 'cache_global_key' );
                    
        if ($cacheEngineClassName !== false)
        {
            $this->cacheEngine = new $cacheEngineClassName();
        }
    }
         
    function __destruct() {
        
    }
    
    static function getMem() {
        if (self::$m_objMem == NULL) {
            self::$m_objMem = new CSCacheAPC();
        }
        return self::$m_objMem;
    }

    function delete($key) {
        if (isset($GLOBALS[$key])) unset($GLOBALS[$key]);
        
        if ( $this->cacheEngine != null )
        {
            $this->cacheEngine->set($this->cacheGlobalKey.$key,false,0);
        }
    }

    function restore($key) {
        
        if (isset($GLOBALS[$key]) && $GLOBALS[$key] !== false) return $GLOBALS[$key];

        if ( $this->cacheEngine != null )
        {       
            $GLOBALS[$key] = $this->cacheEngine->get($this->cacheGlobalKey.$key);
        } else {
            $GLOBALS[$key] = false;
        }
               
        return $GLOBALS[$key];
    }

    function getCacheVersion($cacheVariable, $valuedefault = 1, $ttl = 0)
    {
        
        if (isset($GLOBALS['CacheKeyVersion_'.$cacheVariable])) return $GLOBALS['CacheKeyVersion_'.$cacheVariable];
        
        if ( $this->cacheEngine != null )
        {
            if (($version = $this->cacheEngine->get($this->cacheGlobalKey.$cacheVariable)) == false){
                $version = $valuedefault;
                $this->cacheEngine->set($this->cacheGlobalKey.$cacheVariable,$version,0,$ttl);
                $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $valuedefault;
            } else $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $version;
            
        } else {
            $version = $valuedefault;
            $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $valuedefault;
        }
        
        return $version;        
    }
    
    function increaseCacheVersion($cacheVariable, $valuedefault = 1, $ttl = 0)
    {
        if ( $this->cacheEngine != null )
        {
            if (($version = $this->cacheEngine->get($this->cacheGlobalKey.$cacheVariable)) == false) {
                 $this->cacheEngine->set($this->cacheGlobalKey.$cacheVariable,$valuedefault,0,$ttl);
                 $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $valuedefault;
            } else {$this->cacheEngine->increment($this->cacheGlobalKey.$cacheVariable,$version+1);$GLOBALS['CacheKeyVersion_'.$cacheVariable] = $version+1;}
            
        } else {
            $GLOBALS['CacheKeyVersion_'.$cacheVariable] = $valuedefault;
        }        
    }
    
    function store($key, $value, $ttl = 720000) {        
        if ( $this->cacheEngine != null )
        {
            $GLOBALS[$key] = $value;
            $this->cacheEngine->set($this->cacheGlobalKey.$key,$value,0,$ttl);
        } else {
           $GLOBALS[$key] = $value; 
        }
    }      
}




class erLhcoreClassSystem{
        
    public static function instance()  
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erLhcoreClassSystem();            
        }
        return self::$instance;
    }
    
    static function init($checkDealer = false)
    {
    	$index = "index.php";
		$def_index = '';
       
        $instance = erLhcoreClassSystem::instance();
       
 		$isCGI = (stristr(php_sapi_name(),'cgi') !== false);
        $force_VirtualHost = false;        

        $phpSelf = $_SERVER['PHP_SELF'];

        // Find out, where our files are.
        if ( preg_match( "!(.*/)$index$!", $_SERVER['SCRIPT_FILENAME'], $regs ) )
        {
            $siteDir = $regs[1];
            $index = "/$index";
        }
        elseif ( preg_match( "!(.*/)$index/?!", $phpSelf, $regs ) )
        {
            // Some people using CGI have their $_SERVER['SCRIPT_FILENAME'] not right... so we are trying this.
            $siteDir = $_SERVER['DOCUMENT_ROOT'] . $regs[1];
            $index = "/$index";
        }
        else
        {
            // Fallback... doesn't work with virtual-hosts, but better than nothing
            $siteDir = "./";
            $index = "/$index";
        }
        if ( $isCGI and !$force_VirtualHost )
        {
            $index .= '?';
        }

        // Setting the right include_path
        $includePath = ini_get( "include_path" );
        if ( trim( $includePath ) != "" )
        {
            $includePath = $includePath . /*$instance->envSeparator()*/'/'.  $siteDir;
        }
        else
        {
            $includePath = $siteDir;
        }
        ini_set( "include_path", $includePath );

        $scriptName = $_SERVER['SCRIPT_NAME'];
        // Get the webdir.

        $wwwDir = "";

        if ( $force_VirtualHost )
        {
            $wwwDir = "";
        }
        else
        {
            if ( preg_match( "!(.*)$index$!", $scriptName, $regs ) )
                $wwwDir = $regs[1];
            if ( preg_match( "!(.*)$index$!", $phpSelf, $regs ) )
                $wwwDir = $regs[1];
        }

        if ( ! $isCGI || $force_VirtualHost )
        {
            $requestURI = $_SERVER['REQUEST_URI'];
        }
        else
        {
            $requestURI = $_SERVER['QUERY_STRING'];

            /* take out PHPSESSID, if url-encoded */
            if ( preg_match( "/(.*)&PHPSESSID=[^&]+(.*)/", $requestURI, $matches ) )
            {
                $requestURI = $matches[1].$matches[2];
            }
        }

        // Fallback... Finding the paths above failed, so $_SERVER['PHP_SELF'] is not set right.
        if ( $siteDir == "./" )
            $phpSelf = $requestURI;

        if ( ! $isCGI )
        {
            $index_reg = str_replace( ".", "\\.", $index );
            // Trick: Rewrite setup doesn't have index.php in $_SERVER['PHP_SELF'], so we don't want an $index
            if ( !preg_match( "!.*$index_reg.*!", $phpSelf ) || $force_VirtualHost )
            {
                $index = "";
            }
            else
            {                
                // Get the right $_SERVER['REQUEST_URI'], when using nVH setup.
                if ( preg_match( "!^$wwwDir$index(.*)!", $phpSelf, $req ) )
                {
                    if ( !$req[1] )
                    {
                        if ( $phpSelf != "$wwwDir$index" and preg_match( "!^$wwwDir(.*)!", $requestURI, $req ) )
                        {
                            $requestURI = $req[1];
                            $index = '';
                        }
                        elseif ( $phpSelf == "$wwwDir$index" and
                               ( preg_match( "!^$wwwDir$index(.*)!", $requestURI, $req ) or preg_match( "!^$wwwDir(.*)!", $requestURI, $req ) ) )
                        {
                            $requestURI = $req[1];
                        }
                    }
                    else
                    {
                        $requestURI = $req[1];
                    }
                }
            }
        }
        if ( $isCGI and $force_VirtualHost )
            $index = '';
        // Remove url parameters
        if ( $isCGI and !$force_VirtualHost )
        {
            $pattern = "!(\/[^&]+)!";
        }
        else
        {
            $pattern = "!([^?]+)!";
        }
        if ( preg_match( $pattern, $requestURI, $regs ) )
        {
            $requestURI = $regs[1];
        }

        // Remove internal links
        if ( preg_match( "!([^#]+)!", $requestURI, $regs ) )
        {
            $requestURI = $regs[1];
        }

        if ( !$isCGI )
        {
            $currentPath = substr( $_SERVER['SCRIPT_FILENAME'] , 0, -strlen( 'index.php' ) );
            if ( strpos( $currentPath, $_SERVER['DOCUMENT_ROOT']  ) === 0 )
            {
                $prependRequest = substr( $currentPath, strlen( $_SERVER['DOCUMENT_ROOT'] ) );

                if ( strpos( $requestURI, $prependRequest ) === 0 )
                {
                    $requestURI = substr( $requestURI, strlen( $prependRequest ) - 1 );
                    $wwwDir = substr( $prependRequest, 0, -1 );
                }
            }
        }

        if ( erConfigClassLhConfig::getInstance()->getSetting( 'site', 'classcompile' ) == true ) {
            $instance->compileClass();
        }
    
        $instance->SiteDir = $siteDir;
        $instance->WWWDir = $wwwDir;
        $instance->WWWDirLang = '';
        $instance->IndexFile = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'force_virtual_host' )== false ? $index : '';
        $instance->RequestURI = $requestURI;
        $instance->validDealer = false;
        $instance->domainBase = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'site_domain_base' );

        if ($checkDealer == true)
        {
        	
        }
                
    }

    function compileClass()
    {
        $groups = include('lib/autoloads/lhcompile_group.php');
        
        $cache = CSCacheAPC::getMem();
        $versionSite = $cache->getCacheVersion('site_version');
        foreach ($groups as $group => $classes) {
            
            if ( ($cacheClasses = $cache->restore('ClassCompileCache_'.$group.'_version_'.$versionSite) ) !== false)
            {     
            	include($cacheClasses);
            	return ;
            }
            
            $contentFiles = '';
            $ret = array();
            foreach ($classes as $class => $path) {
                 $refl  = new ReflectionClass($class);
                 $file  = $refl->getFileName();
                 if ($file != '')
                 { 
                    $lines = file($file);                                
                    $start = $refl->getStartLine() - 1;
                    $end   = $refl->getEndLine();                        
                    $ret = array_merge($ret, array_slice($lines, $start, ($end - $start)));    
                 }
            }
            
            file_put_contents('cache/compiledclasses/'.$group.'_class.php','<?php '.implode('',$ret));     
            $cache->store('ClassCompileCache_'.$group.'_version_'.$versionSite, 'cache/compiledclasses/'.$group.'_class.php');
        }
        
        
            
//        print_r($groups);
//        echo "Compiling class";
    }
    
    function wwwDir()
    {
        return $this->WWWDir;
    }
    
    /// The path to where all the code resides
    public $SiteDir;
    /// The access path of the current site view
    /// The relative directory path of the vhless setup
    public $WWWDir;
    
    // The www dir used in links formating
    public $WWWDirLang;
    
    /// The filepath for the index
    public $IndexFile;
    /// The uri which is used for parsing module/view information from, may differ from $_SERVER['REQUEST_URI']
    public $RequestURI;
    /// The type of filesystem, is either win32 or unix. This often used to determine os specific paths.
    
    /// Current language
    public $Language;
    
    // Content language
    public $ContentLanguage;
    
    /// Theme site
    public $ThemeSite;
    
    public $SiteAccess;
        
    public $MobileDevice = false;
    
    private static $instance = null;
        
    public $validDealer = false;
    public $newspaperGroupSite = false;
    public $dealer = false;
    public $dealerUserID = 0;
}


?>