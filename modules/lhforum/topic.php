<?php

$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('version_'.$cache->getCacheVersion('forum_topic_'.(int)$Params['user_parameters']['topic_id']).'forum_topic_view_url'.(int)$Params['user_parameters']['topic_id'].'_page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_user_id_'.erLhcoreClassUser::instance()->getUserID());

if (isset($_POST['PublishTopic']) || ($Result = $cache->restore($cacheKey)) === false)
{
    
$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/topic.tpl.php');
try {
$Album = erLhcoreClassModelForumTopic::fetch((int)$Params['user_parameters']['topic_id']); 
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}   

$message = new erLhcoreClassModelForumMessage();    
if (isset($_POST['PublishTopic']) && erLhcoreClassUser::instance()->isLogged() && erLhcoreClassUser::instance()->hasAccessTo('lhforum','use') && $Album->topic_status != erLhcoreClassModelForumTopic::LOCKED ) {
        $definition = array (              
            'TopicMessage' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
            )
        );
                        
        $form = new ezcInputForm( INPUT_POST, $definition );
        $Errors = array();
                            
        if ( !$form->hasValidData( 'TopicMessage' ) || $form->TopicMessage == '' )
        {
            $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Please enter message!');
        } else {
            $message->content = $form->TopicMessage;
        }
        
        if (count($Errors) == 0)
        {           
            $fileForum = false;
              
            if ( erLhcoreClassImageConverter::isPhoto('LocalFile') ) {
                $photoDir = 'var/forum/'.date('Y').'y/'.date('m').'/'.date('d').'/'.$Album->id;
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
            
            $currentUser = erLhcoreClassUser::instance();
            $message->user_id = $currentUser->getUserID();
            $message->topic_id = $Album->id;
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
            
            erLhcoreClassModule::redirect($Album->url_path.$pageNumberRedirect);
            exit; 
            
                   
        }  else {         
            $tpl->set('errArr',$Errors);
        }
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelForumMessage::getCount(array('disable_sql_cache' => true,'filter' => array('topic_id' => $Album->id)));
$pages->serverURL = $Album->url_path;
$pages->setItemsPerPage((int)erLhcoreClassModelSystemConfig::fetch('posts_per_page')->current_value);
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('topic',$Album);
$tpl->set('message',$message);
    
$Result['content'] = $tpl->fetch();
$Result['path'] = array_merge(array(array('url' => erLhcoreClassDesign::baseurl('forum/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/category','Forum'))),$Album->path);
        
if ($Params['user_parameters_unordered']['page'] > 1) {        
    $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
}
 
$Result['path_base'] = $Album->url_path_base.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : '');

// Store cache only if we are not processing POST
if (!isset($_POST['PublishTopic'])) {
    $cache->store($cacheKey,$Result);
}
    
}

?>