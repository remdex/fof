<?php

class erLhcoreClassDesign
{
    public static function design($path)
    {
    	
    	$debugOutput = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' );
    	
    	if ($debugOutput == true) {
    		$logString = '';
    		$debug = ezcDebug::getInstance(); 
    	}
    	   	
        $instance = erLhcoreClassSystem::instance();  
        foreach ($instance->ThemeSite as $designDirectory)
        {
            $fileDir = $instance->SiteDir . '/design/'. $designDirectory .'/' . $path; 
                       
            if (file_exists($fileDir)) {  
            	
            	if ($debugOutput == true) {
            		$logString .= "Found IN - ".$fileDir."<br/>";          	
            		$debug->log( $logString, 0, array( "source"  => "erLhcoreClassDesign", "category" =>  "design - $path" )  );
            	}
            	
            	return $instance->wwwDir() . '/design/'. $designDirectory .'/' . $path;
            } else { 
            	if ($debugOutput == true)
	            $logString .= "Not found IN - ".$fileDir."<br/>";
            }
        } 
        
        if ($debugOutput == true)
        $debug->log( $logString, 0, array( "source"  => "shop", "erLhcoreClassDesign" =>  "design - $path" )  );
       
    } 
    
    public static function designtpl($path)   
    {
    	$debugOutput = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' );
    	
    	if ($debugOutput == true) {
    		$logString = '';    	
    		$debug = ezcDebug::getInstance();
    	}
    	
        $instance = erLhcoreClassSystem::instance();  
        foreach ($instance->ThemeSite as $designDirectory)
        {
            $tplDir = $instance->SiteDir .'/design/' . $designDirectory .  '/tpl/'. $path;
            
            if (file_exists($tplDir)) {
            	if ($debugOutput == true) {
            		$logString .= "Found IN - ".$tplDir."<br/>";          	
            		$debug->log( $logString, 0, array( "source"  => "erLhcoreClassDesign", "category" =>  "designtpl - $path" )  );
            	}
            	return $tplDir;
            } else {
            	if ($debugOutput == true)
            	$logString .= "Not found IN - ".$tplDir."<br/>";
            }
        }   
          
        if ($debugOutput == true)
        $debug->log( $logString, 0, array( "source"  => "shop", "erLhcoreClassDesign" =>  "designtpl - $path" )  );
          
        return ;
    }
    
    public static function imagePath($path, $useCDN = false, $id = 0)   
    {             
        $instance = erLhcoreClassSystem::instance();
        if ($useCDN == false ) {
        	return erConfigClassLhConfig::getInstance()->getSetting( 'cdn', 'full_img_cdn' ) . $instance->wwwDir() . '/albums/' . $path; 
        } else {
    		$cfg = erConfigClassLhConfig::getInstance();
    		$cdnServers = $cfg->getSetting( 'cdn', 'images' );        		 
    		return $cdnServers[$id % count($cdnServers)] . $instance->wwwDir() . '/albums/' . $path; 
        }
    }
    
    public static function baseurl($link = '',$useTranslation = true)
    {
                
        $instance = erLhcoreClassSystem::instance();
        
        $link = ltrim($link,'/');
        if ($useTranslation == true && $link != '' && erLhcoreClassSystem::instance()->SiteAccess != 'site_admin') {  // Full multilanguage including url addreses            
            self::translateModuleURL($link);
        }
        
        return $instance->WWWDir . $instance->IndexFile .  $instance->WWWDirLang  . '/' . $link;
    }
    
    public static function baseurldirect($link = '')
    {
        $instance = erLhcoreClassSystem::instance();                      
        return $instance->WWWDir . $instance->IndexFile . '/' . ltrim($link,'/');
    }
    
    public static function translateToOriginal($module,$function)
    {
        $cache = CSCacheAPC::getMem();
        
        $cacheKey = 'site_version_'.$cache->getCacheVersion('site_version').'_module_translations_'.erLhcoreClassSystem::instance()->Language;
        
        if (is_null(self::$moduleTranslations) || ( self::$moduleTranslations = $cache->restore($cacheKey) ) ) {
            
            if (file_exists('translations/'.erLhcoreClassSystem::instance()->Language.'/translations.php')) {
                include('translations/'.erLhcoreClassSystem::instance()->Language.'/translations.php');
            } else {
                return array('module' => $module,'function' => $function);
            }
                        
            self::$moduleTranslations['moduleTranslations'] = $moduleTranslations;
            self::$moduleTranslations['moduleTranslationsReverse'] = array_flip($moduleTranslations);
            self::$moduleTranslations['moduleViewTranslations'] = $moduleViewTranslations;   
            
            $cache->store($cacheKey,self::$moduleTranslations);      
        }
        
        $moduleTranslated = $module;
        $functionTranslated = $function;  
             
        if (key_exists($module,self::$moduleTranslations['moduleTranslations'])) {            
            $moduleTranslated = self::$moduleTranslations['moduleTranslations'][$module];               
        }
        
        if (key_exists($moduleTranslated,self::$moduleTranslations['moduleViewTranslations']))
        {
            if (key_exists($function,self::$moduleTranslations['moduleViewTranslations'][$moduleTranslated])) { 
                $functionTranslated = self::$moduleTranslations['moduleViewTranslations'][$moduleTranslated][$function];
            }
        }
        
        return array('module' => $moduleTranslated,'function' => $functionTranslated);
        
    }
    
    public static function translateModuleURL(& $link) {        
            	    	
    	if (erLhcoreClassSystem::instance()->SiteAccess != 'site_admin')
    	{
	    	$url_list = erLhAbstractModelUrlAlias::getList(array('filter'=>array( 'url_alias' => $link )));
	    	if ( !empty($url_list) ) {
	    		$urlItem = current($url_list);
	    		$link = $urlItem->url_destination;
	    		return ;
	    	}
    	}
    	    	
        $cache = CSCacheAPC::getMem();
        
        $cacheKey = 'site_version_'.$cache->getCacheVersion('site_version').'_module_translations_'.erLhcoreClassSystem::instance()->Language;
        
        if (is_null(self::$moduleTranslations) || ( self::$moduleTranslations = $cache->restore($cacheKey) ) ) {
            
            if (file_exists('translations/'.erLhcoreClassSystem::instance()->Language.'/translations.php')) {
                include('translations/'.erLhcoreClassSystem::instance()->Language.'/translations.php');
            } else {
                return ;
            }
                       
            self::$moduleTranslations['moduleTranslations'] = $moduleTranslations;
            self::$moduleTranslations['moduleTranslationsReverse'] = array_flip($moduleTranslations);
            self::$moduleTranslations['moduleViewTranslations'] = $moduleViewTranslations;   
            
            $cache->store($cacheKey,self::$moduleTranslations);      
        }
                        
        list($module,$function) = explode('/',$link);
        
        $moduleTranslated = $module;
        $functionTranslated = $function;  
                
        if (key_exists($module,self::$moduleTranslations['moduleTranslationsReverse'])) {            
            $moduleTranslated = self::$moduleTranslations['moduleTranslationsReverse'][$module];               
        }
        
        if (key_exists($module,self::$moduleTranslations['moduleViewTranslations']))
        {
            $moduleFunctions = array_flip(self::$moduleTranslations['moduleViewTranslations'][$module]);
                            
            if (key_exists($function,$moduleFunctions)) {                
                $functionTranslated = $moduleFunctions[$function];
            }
        }
        
        $link = $moduleTranslated . '/' . $functionTranslated; 
    }
    
    public static function designCSS($files)
    {
        $debugOutput = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' );
    	$items = explode(';',$files);
        
    	if ($debugOutput == true) {
    		$logString = '';
    		$debug = ezcDebug::getInstance(); 
    	}
    	    	
    	$filesToCompress = '';
    	foreach ($items as $path)
    	{	
            $instance = erLhcoreClassSystem::instance();  
            foreach ($instance->ThemeSite as $designDirectory)
            {
                $fileDir = $instance->SiteDir . 'design/'. $designDirectory .'/' . $path; 
                           
                
                if (file_exists($fileDir)) {  
                	
                    $fileContent = file_get_contents($fileDir);
                                        
                    if ( preg_match_all("/url\(\s*[\'|\"]?([A-Za-z0-9_\-\/\.\\%?&#]+)[\'|\"]?\s*\)/ix", $fileContent, $urlMatches) )
                    {
                       $urlMatches = array_unique( $urlMatches[1] );
                       $cssPathArray   = explode( '/', '/design/'. $designDirectory .'/' . $path );
                       // Pop the css file name
                       array_pop( $cssPathArray );
                       $cssPathCount = count( $cssPathArray );
                       foreach( $urlMatches as $match )
                       {
                           $match = str_replace( '\\', '/', $match );
                           $relativeCount = substr_count( $match, '../' );
                           // Replace path if it is realtive
                           if ( $match[0] !== '/' and strpos( $match, 'http:' ) === false )
                           {
                               $cssPathSlice = $relativeCount === 0 ? $cssPathArray : array_slice( $cssPathArray  , 0, $cssPathCount - $relativeCount  );
                               $newMatchPath = $instance->wwwDir() . implode('/', $cssPathSlice) . '/' . str_replace('../', '', $match);
                               $fileContent = str_replace( $match, $newMatchPath, $fileContent );
                           }
                       }
                    }
                    
                    $filesToCompress .= $fileContent;
                	break;
                	
                } else { 
                	if ($debugOutput == true)
    	            $logString .= "Not found IN - ".$fileDir."<br/>";
                }
            } 
    	}

    	   	
        $sys = erLhcoreClassSystem::instance()->SiteDir; 
        $filesToCompress = self::optimizeCSS($filesToCompress,3);
        $fileName = md5($filesToCompress.$instance->WWWDirLang);
        $file = $sys . 'cache/compiledtemplates/'.$fileName.'.css'; 
        
        if (!file_exists($file)) {    		   
            file_put_contents($file,$filesToCompress);
        }
        
        return $instance->wwwDir() . '/cache/compiledtemplates/'.$fileName.'.css'; 
    }
    
    public static function mb_wordwrap($str, $width = 75, $break = "\n", $cut = false) {
    	$lines = explode($break, $str);
    	foreach ($lines as &$line) {
    		$line = rtrim($line);
    		if (mb_strlen($line) <= $width)
    			continue;
    		$words = explode(' ', $line);
    		$line = '';
    		$actual = '';
    		foreach ($words as $word) {
    			if (mb_strlen($actual.$word) <= $width)
    				$actual .= $word.' ';
    			else {
    				if ($actual != '')
    					$line .= rtrim($actual).$break;
    				$actual = $word;
    				if ($cut) {
    					while (mb_strlen($actual) > $width) {
    						$line .= mb_substr($actual, 0, $width).$break;
    						$actual = mb_substr($actual, $width);
    					}
    				}
    				$actual .= ' ';
    			}
    		}
    		$line .= trim($actual);
    	}
    	return implode($break, $lines);
    }
    
    public static function getUserLocation($params = array()) {
    	
    	if ( erLhcoreClassSystem::instance()->validDealer == true ) {
	    	if ( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false ) {    		
	    		$parts = @parse_url($_SERVER['HTTP_REFERER']);     		   		
	    		if ($parts !== false && isset($parts['host']) && !empty($parts['host'])) {
	    			$host = str_replace('www.','', $parts['host']);

	    			$newspapers = erLhAbstractModelNewspaper::getList(array('filter' => array('domain' => 'falkirkherald.co.uk'/*$host*/)));
	    				    			
	    			if ( !empty($newspapers) ) {
	    				
	    				$newspaper = array_shift($newspapers);
	    				
	    				setcookie('ul','', time()-24*3600*31,'/','.'.erLhcoreClassSystem::instance()->domainBase);
	    				CSCacheAPC::getMem()->setSession('CacheUserLocation',false);
	    				$GLOBALS['CacheUserLocation'] = false;
	    				
	    				//$GLOBALS['CacheUserLocation'] = array( 'lat' => $newspaper->lat, 'lon' => $newspaper->lon, 'location' => $newspaper->location );
	    				//CSCacheAPC::getMem()->setSession('CacheUserLocation',$GLOBALS['CacheUserLocation']);
	    				//setcookie('ul',$newspaper->location, time()+24*3600*31,'/','.'.erLhcoreClassSystem::instance()->domainBase);
	    				
	    				return $GLOBALS['CacheUserLocation'];
	    			}
	    		}
	    	}
    	}
    	  	
    	if ( isset($GLOBALS['CacheUserLocation']) ) {
    		return $GLOBALS['CacheUserLocation'];
    	} elseif ( isset($_SESSION['CacheUserLocation']) ) {        		
    		return $_SESSION['CacheUserLocation'];
    	}

    	if (isset($_COOKIE['ul']) && $_COOKIE['ul'] != '') {

    	    $location = erLhAbstractModelPostcode::getLocation(erLhcoreClassCharTransform::TransformToURL($_COOKIE['ul'])); 
  	    
    	    if ( $location != false ) {
    	         $GLOBALS['CacheUserLocation'] = array( 'lat' => $location->lat, 'lon' => $location->lon, 'location' => $location->location ); 
    	         CSCacheAPC::getMem()->setSession('CacheUserLocation',$GLOBALS['CacheUserLocation']);
    		     return $GLOBALS['CacheUserLocation'];
    	    }
    	}
    	
    	$GLOBALS['CacheUserLocation'] = false;
    	CSCacheAPC::getMem()->setSession('CacheUserLocation',false);
    	return $GLOBALS['CacheUserLocation'];
    	
    	/*$ip_location = geoip_record_by_name($_SERVER['REMOTE_ADDR']);

    	if ( isset($ip_location['latitude']) ) {
    		$GLOBALS['CacheUserLocation'] = array( 'lat' => $ip_location['latitude'], 'lon' => $ip_location['longitude'] );    		
    		$GLOBALS['CacheUserLocation']['location'] = erLhcoreClassPN::getNearestLocation(array('sort' => '@geodist ASC','limit' => 1, 'filtergeo' => array('distance' => 100,'lat' => $ip_location['latitude'],'lon' => $ip_location['longitude'])));
    		    		
    		CSCacheAPC::getMem()->setSession('CacheUserLocation',$GLOBALS['CacheUserLocation']);
    		return $GLOBALS['CacheUserLocation'];
    	}

    	$GLOBALS['CacheUserLocation'] = false;
    	CSCacheAPC::getMem()->setSession('CacheUserLocation',false);*/
    	    	
    	return $GLOBALS['CacheUserLocation'];
    }
    
    public static function shrt($string = '',$max = 10,$append = '...', $wordrap = 30)
    {
    	$string = str_replace('&nbsp;',' ',$string);
    	$string = str_replace(
    			array('[b]','[/b]','[b]','[/b]','[ul]','[/ul]','[li]','[/li]','[i]','[/i]'),
    			''
    			,strip_tags($string));
    
    	$string = self::mb_wordwrap($string,$wordrap,"\n",true);
    
    	if (mb_strlen($string) <= $max) return htmlspecialchars($string);
    	$cutted = mb_strcut($string,0,$max,'UTF-8').$append;
    
    	return htmlspecialchars($cutted);
    }
    
    public static function designJS($files)
    {
        $debugOutput = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'debug_output' );
    	$items = explode(';',$files);
        
    	if ($debugOutput == true) {
    		$logString = '';
    		$debug = ezcDebug::getInstance(); 
    	}
    	    	
    	$filesToCompress = '';
    	foreach ($items as $path)
    	{	
            $instance = erLhcoreClassSystem::instance();  
            foreach ($instance->ThemeSite as $designDirectory)
            {
                $fileDir = $instance->SiteDir . 'design/'. $designDirectory .'/' . $path; 
                                           
                if (file_exists($fileDir)) {  
                	
                    $fileContent = file_get_contents($fileDir);
                                        
                    // normalize line feeds
                    $script = str_replace( array( "\r\n", "\r" ), "\n", $fileContent );
        
                    // remove multiline comments
                    $script = preg_replace( '!(?:\n|\s|^)/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $script );
                    $script = preg_replace( '!(?:;)/\*[^*]*\*+([^/][^*]*\*+)*/!', ';', $script );
        
                    // remove whitespace from start & end of line + singelline comment + multiple linefeeds
                    $script = preg_replace( array( '/\n\s+/', '/\s+\n/', '#\n\s*//.*#', '/\n+/' ), "\n", $script );
                                     
                    $filesToCompress .= $script."\n";
                	break;
                	
                } else { 
                	if ($debugOutput == true)
    	            $logString .= "Not found IN - ".$fileDir."<br/>";
                }
            } 
    	}
   	   	
        $sys = erLhcoreClassSystem::instance()->SiteDir;        
        $fileName = md5($filesToCompress.$instance->WWWDirLang);
        $file = $sys . 'cache/compiledtemplates/'.$fileName.'.js'; 
        
        if (!file_exists($file)) {    		   
            file_put_contents($file,$filesToCompress);
        }
        
        return $instance->wwwDir() . '/cache/compiledtemplates/'.$fileName.'.js'; 
    }
    
    
    /**
     * 'compress' css code by removing whitespace
     *
     * @param string $css Concated Css string
     * @param int $packLevel Level of packing, values: 2-3
     * @return string
     */
    static function optimizeCSS( $css, $packLevel )
    {
        // normalize line feeds
        $css = str_replace(array("\r\n", "\r"), "\n", $css);

        // remove multiline comments
        $css = preg_replace('!(?:\n|\s|^)/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = preg_replace('!(?:;)/\*[^*]*\*+([^/][^*]*\*+)*/!', ';', $css);

        // remove whitespace from start and end of line + multiple linefeeds
        $css = preg_replace(array('/\n\s+/', '/\s+\n/', '/\n+/'), "\n", $css);

        if ( $packLevel > 2 )
        {
            // remove space around ':' and ','
            $css = preg_replace(array('/:\s+/', '/\s+:/'), ':', $css);
            $css = preg_replace(array('/,\s+/', '/\s+,/'), ',', $css);

            // remove unnecesery line breaks
            $css = str_replace(array(";\n", '; '), ';', $css);
            $css = str_replace(array("}\n","\n}", ';}'), '}', $css);
            $css = str_replace(array("{\n", "\n{", '{;'), '{', $css);

            // optimize css
            $css = str_replace(array(' 0em', ' 0px',' 0pt', ' 0pc'), ' 0', $css);
            $css = str_replace(array(':0em', ':0px',':0pt', ':0pc'), ':0', $css);
            $css = str_replace(' 0 0 0 0;', ' 0;', $css);
            $css = str_replace(':0 0 0 0;', ':0;', $css);

            // these should use regex to work on all colors
            $css = str_replace(array('#ffffff','#FFFFFF'), '#fff', $css);
            $css = str_replace('#000000', '#000', $css);
        }
        return $css;
    }
    
    
    private static $moduleTranslations = null;
}


?>