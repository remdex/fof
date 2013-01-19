<?php

$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('forum_category_'.(int)$Params['user_parameters']['category_id']).'forum_category_view_url'.(int)$Params['user_parameters']['category_id'].'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_user_id_'.erLhcoreClassUser::instance()->getUserID());

if (isset($_POST['PublishTopic']) || ($Result = $cache->restore($cacheKey)) === false)
{
    $tpl = erLhcoreClassTemplate::getInstance( 'lhforum/category.tpl.php');
       
    try {
        $Category = erLhcoreClassForum::getSession()->load( 'erLhcoreClassModelForumCategory', (int)$Params['user_parameters']['category_id'] );
    } catch (Exception $e){
        erLhcoreClassModule::redirect('/');
        exit;
    } 
    
    $topic = new erLhcoreClassModelForumTopic();
    $topic->setCategory($Category);
    
    $message = new erLhcoreClassModelForumMessage();
    if (isset($_POST['PublishTopic']) && erLhcoreClassUser::instance()->isLogged() && erLhcoreClassUser::instance()->hasAccessTo('lhforum','use')) {
            $definition = array (
                'TopicName' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
                ),
                'TopicMessage' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
                )
            );
            
            // User has to choose one of parent category
            if ($Category->has_subcategorys === true) {                
                $definition['TopicCategory'] = new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'int',  array( 'min_range' => 1)
                );
            }
            
            $form = new ezcInputForm( INPUT_POST, $definition );
            $Errors = array();
            
            if ( !$form->hasValidData( 'TopicName' ) || $form->TopicName == '' )
            {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/category','Please enter topic name!');
            } else {
                $topic->topic_name = $form->TopicName;
            }
            
            if ( !$form->hasValidData( 'TopicMessage' ) || $form->TopicMessage == '' )
            {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/category','Please enter message!');
            } else {
                $message->content = $form->TopicMessage;
            }
            
            if ($Category->has_subcategorys === true && !$form->hasValidData( 'TopicCategory' )) {
                $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/category','Please choose category!');
            } elseif ($Category->has_subcategorys === true && $form->hasValidData( 'TopicCategory' )) {
                try {
                    $categoryParent = erLhcoreClassModelForumCategory::fetch($form->TopicCategory);                    
                    if ($categoryParent->parent = $Category->id) {
                        $topic->setCategory($categoryParent);
                    } else {
                        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/category','Not parent category!');
                    }
                } catch (Exception $e){
                    $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/category','Invalid category!');
                }
            }
               
             
            if (count($Errors) == 0)
            {             
                $currentUser = erLhcoreClassUser::instance();
                $topic->user_id = $currentUser->getUserID(); 
                $message->user_id = $currentUser->getUserID(); 
                   
                $topic->ctime = time();
                $topic->saveThis();
                $message->topic_id = $topic->id;
                                
                $fileForum = false;                  
                if ( erLhcoreClassImageConverter::isPhoto('LocalFile') ) {
                    $photoDir = 'var/forum/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$message->topic_id;
                    erLhcoreClassImageConverter::mkdirRecursive($photoDir);
    
                    $fileNamePhysic = erLhcoreClassImageConverter::sanitizeFileName($_FILES['LocalFile']['name']);
    
        	        if (file_exists($photoDir.'/'.$fileNamePhysic)) {
        	       		$fileNamePhysic = erLhcoreClassModelForgotPassword::randomPassword(5).time().'-'.$fileNamePhysic;
        	        }
    
                    erLhcoreClassImageConverter::getInstance()->converter->transform( 'photoforum', $_FILES['LocalFile']['tmp_name'], $photoDir.'/'.$fileNamePhysic );                    
                    chmod($photoDir.'/'.$fileNamePhysic,erConfigClassLhConfig::getInstance()->getSetting( 'site', 'StorageFilePermissions' ));                    
                    
                    $instance = erLhcoreClassSystem::instance();                    
                    $message->content .= "\n[img]" . $instance->wwwDir() . '/' . $photoDir . '/' . $fileNamePhysic.'[/img]';
                    
                    $fileForum = new erLhcoreClassModelForumFile();
                    $fileForum->name = $fileNamePhysic;
                    $fileForum->file_path =  $photoDir;
                    $fileForum->file_size =  filesize($photoDir.'/'.$fileNamePhysic);                    
                }
    
                $message->ctime = time();
                $message->ip = $_SERVER['REMOTE_ADDR'];
                $message->saveThis();
    
                if ($fileForum instanceof erLhcoreClassModelForumFile) {
                    $fileForum->message_id = $message->id;
                    $fileForum->saveThis();
                }
                
                $pageNumberRedirect = '';
                if ($Params['user_parameters_unordered']['page'] > 1) {
                    $pageNumberRedirect .= '/(page)/'.$Params['user_parameters_unordered']['page'];
                }
                
                erLhcoreClassModule::redirect($Category->path_url.$pageNumberRedirect);
                exit;
                
                 
            }  else {         
                $tpl->set('errArr',$Errors);
            }
    }
    
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelForumTopic::getCount(array('filter' => array('path_'.$Category->depth => $Category->id)));
    $pages->setItemsPerPage(16);
    $pages->serverURL = $Category->path_url;
    $pages->paginate();
      
    $tpl->set('pagesCurrent',$pages);
    $tpl->set('category',$Category);
    
    $tpl->set('topic',$topic);
    $tpl->set('message',$message);
    
    $Result['content'] = $tpl->fetch();  
    $Result['path'] = array_merge(array(array('url' => erLhcoreClassDesign::baseurl('forum/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('forum/category','Forum'))),$Category->path_site);
    
    if ($Params['user_parameters_unordered']['page'] > 1) {        
        $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/category','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
    }
    
    $Result['path_base'] = $Category->url_path_base.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : '');
    
    // Store cache only if we are not processing POST
    if (!isset($_POST['PublishTopic'])) {
        $cache->store($cacheKey,$Result);
    }
}

?>