<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/editmessage.tpl.php');

$message = $Params['user_object'] ;

if (isset($_POST['UpdateMessage']))
{      
    $definition = array (              
                'TopicMessage' => new ezcInputFormDefinitionElement(
                    ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
                )
            );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
                       
    if ( !$form->hasValidData( 'TopicMessage' ) || $form->TopicMessage == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/editmessage','Please enter message!');
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
        
        if ($fileForum instanceof erLhcoreClassModelForumFile) {
            $fileForum->message_id = $message->id;
            $fileForum->saveThis();
        }
        
        $message->saveThis();        
        erLhcoreClassModule::redirect($message->topic->url_path);
        exit;  
         
    }  else { 
      
        $tpl->set('errArr',$Errors);
    }            
}

$tpl->set('message',$message);

$Result['content'] = $tpl->fetch();
$Result['path'] = array_merge(array(array('url' => erLhcoreClassDesign::baseurl('forum/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('forum/editmessage','Forum'))),$message->topic->path);
$Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('forum/editmessage','Message edit'));

