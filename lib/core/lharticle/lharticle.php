<?php

class erLhcoreClassArticle {
         
   public static function getSession($type = false)
   {                
        if ($type === false && !isset( self::$persistentSession ) )
        {
            self::$persistentSession = new ezcPersistentSession(
                ezcDbInstance::get(),
                new ezcPersistentCodeManager( './pos/lharticle' )
            );
        } elseif ($type !== false && !isset( self::$persistentSessionSlave ) ) {            
            self::$persistentSessionSlave = new ezcPersistentSession(
                ezcDbInstance::get($type),
                new ezcPersistentCodeManager( './pos/lharticle' )
            );
        }
        
        return $type === false ? self::$persistentSession : self::$persistentSessionSlave;
        
   }
   
   public static function validateStaticArticle(& $Static) {
       $definition = array(
    		'ArticleBody' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'ArticleName' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		)
        );
        
        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
        
        if ( !$form->hasValidData( 'ArticleName' ) || $form->ArticleName == '' ) {
        	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter article name');
        } else {
            $Static->name = $form->ArticleName;
        }
        
        if ( !$form->hasValidData( 'ArticleBody' ) || $form->ArticleBody == '' ) {
        	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter article body');
        } else {
            $Static->content = $form->ArticleBody;
        }
        
        return $Errors;
   }
   
   public static function validateCategory(& $Static) {
       $definition = array(
    		'CategoryPos' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'int'
    		),
    		'CategoryName' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'Intro' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'HideArticles' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
    		)
        );
        
        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
        
        if ( $form->hasValidData( 'HideArticles' ) && $form->HideArticles == true ) {
        	$Static->hide_articles = 1;
        } else {
            $Static->hide_articles = 0;
        }
        
        if ( !$form->hasValidData( 'CategoryName' ) || $form->CategoryName == '' ) {
        	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter category name');
        } else {
            $Static->category_name = $form->CategoryName;
        }
        
        if ( $form->hasValidData( 'Intro' ) ) {    
            $Static->intro = $form->Intro;
        }
        
        if ( !$form->hasValidData( 'CategoryPos' ) ) {  
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter category position');        
        } else {
            $Static->pos = $form->CategoryPos;
        }
        
        return $Errors;
   }
   
   public static function validateArticle(& $Article) {
       
       $definition = array(
    		'ArticleName' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'AliasURL' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'AlternativeURL' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'IsModal' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
    		),
    		'pos' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'int'
    		),
    		'ArticleIntro' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		),
    		'ArticleBody' => new ezcInputFormDefinitionElement(
    				ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
    		)
        );
        
        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
        
        if ( !$form->hasValidData( 'ArticleName' ) || $form->ArticleName == '' ) {
        	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter article name');
        } else {
            $Article->article_name = $form->ArticleName;
        }
        
        if ( $form->hasValidData( 'AliasURL' ) ) {        	
            $Article->alias_url = $form->AliasURL;
        } else {
            $Article->alias_url = '';
        }
        
        if ( $form->hasValidData( 'AlternativeURL' ) ) {        	
            $Article->alternative_url = $form->AlternativeURL;
        } else {
            $Article->alternative_url = '';
        }
        
        if ( $form->hasValidData( 'IsModal' ) && $form->IsModal == true ) {        	
            $Article->is_modal = 1;
        } else {
            $Article->is_modal = 0;
        }
                
        if ( !$form->hasValidData( 'pos' ) ) {  
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter article position');        
        } else {
            $Article->pos = $form->pos;
        }
                
        if ( !$form->hasValidData( 'ArticleIntro' ) || $form->ArticleIntro == '' ) {
        	$Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('user/new','Please enter article intro');
        } else {
            $Article->intro = $form->ArticleIntro;
        }
        
        if ( $form->hasValidData( 'ArticleBody' )  ) {
        	$Article->body = $form->ArticleBody;
        }        
                
        $uploadLogo = false; 
        
        if ( empty($Errors) ) {  
            
            if (!is_numeric($Article->id)){
                $Article->saveThis();
            }
                     
            if ($_FILES["ArticleThumb"]["error"] != 4) {
                if (isset($_FILES["ArticleThumb"]) && is_uploaded_file($_FILES["ArticleThumb"]["tmp_name"]) && $_FILES["ArticleThumb"]["error"] == 0 && erLhcoreClassImageConverter::isPhoto('ArticleThumb'))
            	{    		
            	    if($Article->has_photo){
            			$Article->removePhotos();
            	    }    	    
            		$uploadLogo = true;
            		
            	} else {
            	    $Errors[] =  'Incorrect photo file!';
            	}
            }
            
            $delete_logo = false;
        	if (isset($_POST['DeletePhoto']) && $_POST['DeletePhoto'] == 1) {		
               $Article->removePhoto();        	       
        	}
        	
            $config = erConfigClassLhConfig::getInstance();
            
            if ($uploadLogo == true)
            {                
               $photoDir = 'var/media/'.$Article->id;
               if (!file_exists($photoDir))
               mkdir($photoDir,$config->getSetting( 'site', 'StorageDirPermissions' ));
        
               $photoDir = 'var/media/'.$Article->id.'/images';
               if (!file_exists($photoDir))
               mkdir($photoDir,$config->getSetting( 'site', 'StorageDirPermissions' ));	       
        
               $fileName = erLhcoreClassSearchHandler::moveUploadedFile('ArticleThumb',$photoDir . '/' );
                   
               $Article->has_photo = 1;
               $Article->file_name = $fileName;      
            }
        }
            
        return $Errors;
   }
   
   
   // For all others
   private static $persistentSession;
   
   // For selects
   private static $persistentSessionSlave;
}

?>