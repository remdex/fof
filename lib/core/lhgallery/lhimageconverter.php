<?php


class erLhcoreClassImageConverter {
      
   public $converter;
   private static $instance = null;
   
   function __construct()
   {
       $conversionSettings = array();
       
       if (erConfigClassLhConfig::getInstance()->getSetting( 'site', 'imagemagic_enabled' ) == true)
       {
           $conversionSettings[] = new ezcImageHandlerSettings( 'imagemagick', 'erLhcoreClassGalleryImagemagickHandler' );
       }
       
       $conversionSettings[] =  new ezcImageHandlerSettings( 'gd','erLhcoreClassGalleryGDHandler' );
       
        $this->converter = new ezcImageConverter(
                new ezcImageConverterSettings(
                    $conversionSettings
                )
            );

            $filterNormal = array();
            $filterWatermarkAll = array(); 
                        
            $this->converter->createTransformation(
                'photow_60',
                array( 
                    new ezcImageFilter( 
                        'croppedThumbnail',
                        array( 
                            'width'     => 60, 
                            'height'    => 60,                            
                            'direction' => ezcImageGeometryFilters::SCALE_DOWN,
                        )
                    ),
                ),
                array( 
                    'image/jpeg',
                    'image/png',
                ),
                new ezcImageSaveOptions(array('quality' => (int)95))
            );
                      
            $this->converter->createTransformation( 'jpeg', $filterWatermarkAll,
                array( 
                    'image/jpeg',
                    'image/png',
                    //Supported by GD
//                   'image/tiff',                    
//                   'image/tga',
//                   'image/svg+xml',
//                   'image/svg+xml',
                    'image/gif',
                ),
                new ezcImageSaveOptions(array('quality' => (int)erLhcoreClassModelSystemConfig::fetch('full_image_quality')->current_value)) ); 
                         
            // Titulinio puslapio pagrindine naujiena
            $this->converter->createTransformation(
                'thumbarticle',
                array( 
                    new ezcImageFilter( 
                        erLhcoreClassModelSystemConfig::fetch('thumbnail_scale_algorithm')->current_value,
                        array( 
                            'width'     => 300,
                            'height'    => 300,
                            'direction' => ezcImageGeometryFilters::SCALE_DOWN,
                        )
                    ),
                ),
                array( 
                    'image/jpeg',
                    'image/png',
                ),
                new ezcImageSaveOptions(array('quality' => (int)erLhcoreClassModelSystemConfig::fetch('thumbnail_quality_default')->current_value))
            );
                        
            $this->converter->createTransformation(
                'thumbsmall_article',
                array( 
                    new ezcImageFilter( 
                        erLhcoreClassModelSystemConfig::fetch('thumbnail_scale_algorithm')->current_value,
                        array( 
                            'width'     => 82, 
                            'height'    => 63,                            
                            'direction' => ezcImageGeometryFilters::SCALE_DOWN,
                        )
                    ),
                ),
                array( 
                    'image/jpeg',
                    'image/png',
                ),
                new ezcImageSaveOptions(array('quality' => (int)erLhcoreClassModelSystemConfig::fetch('thumbnail_quality_default')->current_value))
            );                         
                    
        }
   
   
    public static function getInstance()  
    {
        if ( is_null( self::$instance ) )
        {          
            self::$instance = new erLhcoreClassImageConverter();            
        }
        return self::$instance;
    }
    
    public static function isPhoto($file)
    { 
       if ($_FILES[$file]['error'] == 0)
       {       
           try {
               $image = new ezcImageAnalyzer( $_FILES[$file]['tmp_name'] );            
               if ($image->data->size < ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) && $image->data->width > 10 && $image->data->height > 10)
               {                   
                   return true;                   
                   
               } else 
               
               return false;
           } catch (Exception $e) {
               return false;
           }
       
       } else {
           return false;
       } 
    }
    
    public static function handleUpload(& $image,$params = array())
    {        
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        
        $config = erConfigClassLhConfig::getInstance();
        
        if ($config->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
        {
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $params['file_upload_path'], $photoDir.'/normal_'.$fileNamePhysic ); 
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $params['file_upload_path'], $photoDir.'/thumb_'.$fileNamePhysic ); 
            
            chmod($photoDir.'/normal_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
            chmod($photoDir.'/thumb_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
            
            $dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
            // If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
            if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
            	erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $params['file_upload_path'], $photoDir.'/'.$fileNamePhysic ); 
            } else  {
                rename($params['file_upload_path'],$photoDir.'/'.$fileNamePhysic);
            }
           
            chmod($photoDir.'/'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
           
            $image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
            $image->total_filesize = filesize($photoDir.'/'.$fileNamePhysic)+filesize($photoDir.'/thumb_'.$fileNamePhysic)+filesize($photoDir.'/normal_'.$fileNamePhysic);
            $image->filepath = $params['photo_dir_photo'];
           
            $imageAnalyze = new ezcImageAnalyzer( $photoDir.'/'.$fileNamePhysic ); 	       
            $image->pwidth = $imageAnalyze->data->width;
            $image->pheight = $imageAnalyze->data->height;
            $image->filename = $fileNamePhysic;
            
            if (file_exists($params['file_upload_path'])){
                unlink($params['file_upload_path']);
            }
            
        } elseif ($config->getSetting( 'site', 'file_storage_backend' ) == 'amazons3') { 
            $fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().$fileNamePhysic;  

            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $params['file_upload_path'], 'var/tmpupload/normal_'.$fileNamePhysic );                         
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb', $params['file_upload_path'], 'var/tmpupload/thumb_'.$fileNamePhysic ); 
                       
            $dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
            // If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
            if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
            	erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $params['file_upload_path'], 'var/tmpupload/'.$fileNamePhysic ); 
            } else  {
           		rename($params['file_upload_path'],'var/tmpupload/'.$fileNamePhysic);
            }
                        
            $image->filesize = filesize('var/tmpupload/'.$fileNamePhysic);
            $image->total_filesize = filesize('var/tmpupload/'.$fileNamePhysic)+filesize('var/tmpupload/thumb_'.$fileNamePhysic)+filesize('var/tmpupload/normal_'.$fileNamePhysic);
            $image->filepath = $params['photo_dir_photo'];
            
            $imageAnalyze = new ezcImageAnalyzer( 'var/tmpupload/'.$fileNamePhysic ); 	       
            $image->pwidth = $imageAnalyze->data->width;
            $image->pheight = $imageAnalyze->data->height;
                       
            S3::setAuth($config->getSetting( 'amazons3', 'aws_access_key' ), $config->getSetting( 'amazons3', 'aws_secret_key'));            
            S3::putObject(S3::inputFile('var/tmpupload/thumb_' . $fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/thumb_'.$fileNamePhysic, S3::ACL_PUBLIC_READ);
            S3::putObject(S3::inputFile('var/tmpupload/normal_' . $fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/normal_'.$fileNamePhysic, S3::ACL_PUBLIC_READ);
            S3::putObject(S3::inputFile('var/tmpupload/' . $fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/'.$fileNamePhysic, S3::ACL_PUBLIC_READ);
            
            $image->filename = $fileNamePhysic;
            
            if (file_exists($params['file_upload_path'])){
                unlink($params['file_upload_path']);
            }
            
            unlink('var/tmpupload/'.$fileNamePhysic);
            unlink('var/tmpupload/normal_'.$fileNamePhysic);
            unlink('var/tmpupload/thumb_'.$fileNamePhysic);
        }
    }
    
    // Handles uploads from archive
    public static function handleUploadLocal(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $fileSession = $params['file_session'];
        $pathExtracted = $params['post_file_name'];
        $album = $params['album'];
        
        $config = erConfigClassLhConfig::getInstance();
        
        if ($config->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
        {
            $wwwUser = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_user' );
       		$wwwUserGroup = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_group' );
       		    
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $pathExtracted, $photoDir.'/normal_'.$fileNamePhysic );
        	erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb',$pathExtracted, $photoDir.'/thumb_'.$fileNamePhysic );
        					    	
        	$dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
    		// If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
    		if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
    				erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $pathExtracted, $photoDir.'/'.$fileNamePhysic ); 
    		} else  {
    				rename($pathExtracted,$photoDir.'/'.$fileNamePhysic);
    		}
    		
        	chown($photoDir.'/'.$fileNamePhysic,$wwwUser);
        	chown($photoDir.'/normal_'.$fileNamePhysic,$wwwUser);
        	chown($photoDir.'/thumb_'.$fileNamePhysic,$wwwUser);
        	
        	chgrp($photoDir.'/'.$fileNamePhysic,$wwwUserGroup);
        	chgrp($photoDir.'/normal_'.$fileNamePhysic,$wwwUserGroup);
        	chgrp($photoDir.'/thumb_'.$fileNamePhysic,$wwwUserGroup);
        					    					    	
        	chmod($photoDir.'/'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	chmod($photoDir.'/normal_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	chmod($photoDir.'/thumb_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	
        	$image->filesize = filesize($photoDir.'/'.$fileNamePhysic);
        	$image->total_filesize = filesize($photoDir.'/'.$fileNamePhysic)+filesize($photoDir.'/thumb_'.$fileNamePhysic)+filesize($photoDir.'/normal_'.$fileNamePhysic);
        	$image->filepath = $params['photo_dir_photo'];
    
        	$imageAnalyze = new ezcImageAnalyzer( $photoDir.'/'.$fileNamePhysic );
        	$image->pwidth = $imageAnalyze->data->width;
        	$image->pheight = $imageAnalyze->data->height;
        	$image->hits = 0;
        } elseif ($config->getSetting( 'site', 'file_storage_backend' ) == 'amazons3') { 

            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $pathExtracted, 'var/tmpupload/normal_'.$fileNamePhysic );
        	erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb',$pathExtracted, 'var/tmpupload/thumb_'.$fileNamePhysic );

        	$dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
    		// If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
    		if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
    				erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $pathExtracted, $pathExtracted ); 
    		}

        	$image->filesize = filesize($pathExtracted);
        	$image->total_filesize = filesize($pathExtracted)+filesize('var/tmpupload/normal_'.$fileNamePhysic )+filesize('var/tmpupload/thumb_'.$fileNamePhysic);
        	$image->filepath = $params['photo_dir_photo'];

        	$imageAnalyze = new ezcImageAnalyzer( $pathExtracted );
        	$image->pwidth = $imageAnalyze->data->width;
        	$image->pheight = $imageAnalyze->data->height;
        	$image->hits = 0;

        	S3::setAuth($config->getSetting( 'amazons3', 'aws_access_key' ), $config->getSetting( 'amazons3', 'aws_secret_key'));            
            S3::putObject(S3::inputFile('var/tmpupload/thumb_' . $fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/thumb_' . $fileNamePhysic, S3::ACL_PUBLIC_READ);
            S3::putObject(S3::inputFile('var/tmpupload/normal_' . $fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/normal_' . $fileNamePhysic, S3::ACL_PUBLIC_READ);
            S3::putObject(S3::inputFile($pathExtracted, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/' . $fileNamePhysic, S3::ACL_PUBLIC_READ);

        	unlink($pathExtracted);
            unlink('var/tmpupload/normal_'.$fileNamePhysic);
            unlink('var/tmpupload/thumb_'.$fileNamePhysic);
        }
    }
    
    // Handles uploads from batch
    public static function handleUploadBatch(& $image,$params = array())
    {
        $photoDir = $params['photo_dir'];
        $fileNamePhysic = $params['file_name_physic'];
        $imagePath = $params['post_file_name'];

        $config = erConfigClassLhConfig::getInstance();

        if ($config->getSetting( 'site', 'file_storage_backend' ) == 'filesystem')
        {
            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $imagePath, $photoDir.'/normal_'.$fileNamePhysic );
        	erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb',$imagePath, $photoDir.'/thumb_'.$fileNamePhysic );
        					    	
        	$dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
    		// If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
    		if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
    				erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $imagePath, $imagePath ); 
    				chmod($imagePath,$config->getSetting( 'site', 'StorageFilePermissions' ));
    		}
    		
        	chmod($photoDir.'/normal_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	chmod($photoDir.'/thumb_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	
        	$image->filesize = filesize($imagePath);
            $image->total_filesize = $image->filesize;
    
        	$imageAnalyze = new ezcImageAnalyzer( $imagePath ); 	       
            $image->pwidth = $imageAnalyze->data->width;
            $image->pheight = $imageAnalyze->data->height;
    
        	$image->hits = 0;
        	 	
        } elseif ($config->getSetting( 'site', 'file_storage_backend' ) == 'amazons3') { 

            erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumbbig', $imagePath, $photoDir.'/normal_'.$fileNamePhysic );
        	erLhcoreClassImageConverter::getInstance()->converter->transform( 'thumb',$imagePath, $photoDir.'/thumb_'.$fileNamePhysic );
        					    	
        	$dataWatermark = erLhcoreClassModelSystemConfig::fetch('watermark_data')->data;	       
    		// If watermark have to be applied we use conversion othwrwise just upload original to avoid any quality loose.
    		if ($dataWatermark['watermark_disabled'] == false && $dataWatermark['watermark_enabled_all'] == true) {	       	
    				erLhcoreClassImageConverter::getInstance()->converter->transform( 'jpeg', $imagePath, $imagePath ); 
    				chmod($imagePath,$config->getSetting( 'site', 'StorageFilePermissions' ));
    		}
    		
        	chmod($photoDir.'/normal_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	chmod($photoDir.'/thumb_'.$fileNamePhysic,$config->getSetting( 'site', 'StorageFilePermissions' ));
        	
        	$image->filesize = filesize($imagePath);
            $image->total_filesize = $image->filesize;

        	$imageAnalyze = new ezcImageAnalyzer( $imagePath ); 	       
            $image->pwidth = $imageAnalyze->data->width;
            $image->pheight = $imageAnalyze->data->height;
            
        	$image->hits = 0;
    
        	S3::setAuth($config->getSetting( 'amazons3', 'aws_access_key' ), $config->getSetting( 'amazons3', 'aws_secret_key'));            
            S3::putObject(S3::inputFile($photoDir.'/thumb_'.$fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/thumb_' . $fileNamePhysic, S3::ACL_PUBLIC_READ);
            S3::putObject(S3::inputFile($photoDir.'/normal_'.$fileNamePhysic, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/normal_' . $fileNamePhysic, S3::ACL_PUBLIC_READ);
            S3::putObject(S3::inputFile($imagePath, false), $config->getSetting( 'amazons3', 'bucket' ), $photoDir . '/' . $fileNamePhysic, S3::ACL_PUBLIC_READ);
        	
            // Delete created variations, because they are in cloud now
            unlink($photoDir.'/normal_'.$fileNamePhysic);
        	unlink($photoDir.'/thumb_'.$fileNamePhysic);
        }
    }
    
    
    
    public static function isPhotoLocal($filePAth)
    {              
           try {
               $image = new ezcImageAnalyzer( $filePAth );            
               if ($image->data->size < ((int)erLhcoreClassModelSystemConfig::fetch('max_photo_size')->current_value*1024) && $image->data->width > 10 && $image->data->height > 10)
               {                   
                   return true;                   
                   
               } else                
               return false;
           } catch (Exception $e) {
               return false;
           }  
    }
    
    // Borowed from coppermine gallery
    public static function sanitizeFileName($str)
    {  
       static $forbidden_chars;
      if (!is_array($forbidden_chars)) {
        $mb_utf8_regex = '[\xE1-\xEF][\x80-\xBF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xC2-\xDF][\x80-\xBF]';
        if (function_exists('html_entity_decode')) {
          $chars = html_entity_decode('$/\\:*?&quot;&#39;&lt;&gt;|` &amp;', ENT_QUOTES, 'UTF-8');
        } else {
          $chars = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;', '&nbsp;', '&#39;'), array('&', '"', '<', '>', ' ', "'"), $CONFIG['forbiden_fname_char']);
        }
        preg_match_all("#$mb_utf8_regex".'|[\x00-\x7F]#', $chars, $forbidden_chars);
      }
      /**
       * $str may also come from $_POST, in this case, all &, ", etc will get replaced with entities.
       * Replace them back to normal chars so that the str_replace below can work.
       */
      $str = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $str);;
      $return = str_replace($forbidden_chars[0], '-', $str);
      $return = str_replace(array(')','('), array('',''), $return);
      $return = str_replace(' ', '-', $return);
    
      /**
      * Fix the obscure, misdocumented "feature" in Apache that causes the server
      * to process the last "valid" extension in the filename (rar exploit): replace all
      * dots in the filename except the last one with an underscore.
      */
      // This could be concatenated into a more efficient string later, keeping it in three
      // lines for better readability for now.
      $extension = strtolower(ltrim(substr($return,strrpos($return,'.')),'.'));
      $filenameWithoutExtension = str_replace('.' . $extension, '', $return);
      $return = str_replace('.', '-', $filenameWithoutExtension) .'.' . $extension;
      return $return;
    }
    
    public static function getExtension($fileName) {
        return current(end(explode('.',$fileName)));
    }
    
    
    public static function mkdirRecursive($path, $chown = false) {        
        $partsPath = explode('/',$path);
        $pathCurrent = '';
        
        $config = erConfigClassLhConfig::getInstance();
        $wwwUser = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_user' );
   		$wwwUserGroup = erConfigClassLhConfig::getInstance()->getSetting( 'site', 'default_www_group' );
   		   		
        foreach ($partsPath as $key => $path)
        {
            $pathCurrent .= $path . '/';
            if ( !is_dir($pathCurrent) ) {
                mkdir($pathCurrent,$config->getSetting( 'site', 'StorageDirPermissions' ));
                if ($chown == true){
                    chown($pathCurrent,$wwwUser);
				    chgrp($pathCurrent,$wwwUserGroup);
                }
            }
        }
    }
    
    
    public static function hasFiles($sourceDir)
    {
        if ( !is_dir( $sourceDir ) )
        {
             return true;
        }
        
        $elements = array();
        $d = @dir( $sourceDir );
        if ( !$d )
        {
            return true;
        }

        while ( ( $entry = $d->read() ) !== false )
        {
            if ( $entry == '.' || $entry == '..' )
            {
                continue;
            }
                        
            return true;            
        }      
        
        return false;
    }

    public static function removeRecursiveIfEmpty($basePath,$removePath)
    {
        $removePath = trim($removePath,'/');
        $partsRemove = explode('/',$removePath);

        // Avoid removement of userpics folder
        if ($partsRemove[0] == 'userpics') {
            $basePath .= 'userpics/';
            array_shift($partsRemove);
        }
        
        $pathElementsCount = count($partsRemove);               
        foreach ($partsRemove as $part) {
    		// We found some files/folders, so we have to exit    		
    		if (self::hasFiles( $basePath . implode('/',$partsRemove) ) === true) {
    		    return ;
    		} else {     		
    		    //Folder is empty, delete this folder
    		    @rmdir($basePath . implode('/',$partsRemove));
    		}
    		array_pop($partsRemove);		
        } 
    }
}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    

        $input = fopen("php://input", "r");
        $target = fopen($path, "w");
        $realSize = stream_copy_to_stream($input, $target);
        fclose($input);
        fclose($target);
        
        if ($realSize != $this->getSize()){         
            if (file_exists($path)){
                unlink($path);
            }
            return false;
        }
        
        return true;
    }
    
    function getName() {
        return $_GET['qqfile'];
    }
    
    function getParam($param) {
        if (isset($_GET[$param])) return $_GET[$param]; 
    }
    
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    
    function getParam($param) {
        if (isset($_GET[$param])) return $_GET[$param];        
    }
    
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    private $filePath = null;
    private $fileName = null;
    private $fileSize = null;
    private $fileExtension = null;
    
    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }

    public function getParam($param)
    {
        return $this->file->getParam($param);
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getMimeType()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        return finfo_file($finfo, $this->filePath);
    }
    
    public function getFileName()
    {
        return $this->fileName;
    }

    public function getUserFileName()
    {
        return $this->file->getName();
    }
    
    public function getFileSize()
    {
        return $this->fileSize;
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    public function getFileExtension()
    {
        return $this->fileExtension;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $this->fileSize = $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        $this->fileExtension = $ext;
        
        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        $this->filePath = $uploadDirectory . $filename . '.' . $ext;
        $this->fileName =  $filename . '.' . $ext;
        
        if ( $this->file->save($this->filePath) ) {
            return array('success'=>true);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }        
    }
}

?>