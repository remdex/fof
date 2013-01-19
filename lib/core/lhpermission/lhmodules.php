<?php

class erLhcoreClassModules{
      
   function __construct()
   {
 
   }
   
   public static function getModuleList()
   {       
        $ModulesDir = 'modules';
        
        $ModuleList = array();
        
        $Modules = ezcBaseFile::findRecursive( $ModulesDir,array( '@module\.php$@' ) );
        
        foreach ($Modules as $ModuleInclude)
        {
            include($ModuleInclude); 
            $ModuleList[str_replace('modules/','',dirname($ModuleInclude))] = array('name' => $Module['name']);
        }
        
        
        $cfg = erConfigClassLhConfig::getInstance(); 
        $extensions = $cfg->getSetting('site','extensions');
                    
         // Is it extension module        
        foreach ($extensions as $extension)
        {               
            if (is_dir('extension/'.$extension.'/modules')){                                              
                $ModulesExtensio = ezcBaseFile::findRecursive( 'extension/'.$extension.'/modules',array( '@module\.php$@' ) );
                foreach ($ModulesExtensio as $ModuleInclude)
                {
                    include($ModuleInclude); 
                    $ModuleList[str_replace('extension/'.$extension.'/modules/','',dirname($ModuleInclude))] = array('name' => $Module['name']);
                }             
             }
        }
             
        return $ModuleList ;
   }

   public static function getModuleFunctions($ModulePath)
   {
       $FunctionListCompiled = array();
       
       // Is it core module
       
       if (file_exists('modules/' . $ModulePath . '/module.php')){
            include('modules/' . $ModulePath . '/module.php');
            $FunctionListCompiled = $FunctionList;
       }
       
       
       $cfg = erConfigClassLhConfig::getInstance(); 
       $extensions = $cfg->getSetting('site','extensions');
        
       foreach ($extensions as $extension)
       {   
           if (is_dir('extension/'.$extension.'/modules/'.$ModulePath)) {
                $ModulesExtensio = ezcBaseFile::findRecursive( 'extension/'.$extension.'/modules/'.$ModulePath,array( '@module\.php$@' ) );
                foreach ($ModulesExtensio as $ModuleInclude)
                {
                    include($ModuleInclude);                     
                    $FunctionListCompiled = array_merge($FunctionListCompiled,$FunctionList);
                }
           }
       }
       
       return $FunctionListCompiled;
        
   }

}


?>