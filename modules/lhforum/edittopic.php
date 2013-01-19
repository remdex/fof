<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/edittopic.tpl.php');

$topic = $Params['user_object'] ;

if (isset($_POST['UpdateTopic']))
{
    $definition = array (
            'TopicName' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'unsafe_raw'
            ),
            'TopicStatus' => new ezcInputFormDefinitionElement(
                ezcInputFormDefinitionElement::REQUIRED, 'int'
            )
    );
    
    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();
                       
    if ( !$form->hasValidData( 'TopicName' ) || $form->TopicName == '' )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/edittopic','Please enter topic name!');
    } else {
        $topic->topic_name = $form->TopicName;
    } 
                       
    if ( !$form->hasValidData( 'TopicStatus' ) )
    {
        $Errors[] =  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/edittopic','Please enter topic status!');
    } else {
        $topic->topic_status = $form->TopicStatus;
    }
        
    if (count($Errors) == 0)
    {   
        $topic->saveThis();        
        erLhcoreClassModule::redirect('forum/admintopic/'.$topic->id);
        exit;  
         
    }  else { 
      
        $tpl->set('errArr',$Errors);
    }            
}

$tpl->set('topic',$topic);

$Result['content'] = $tpl->fetch();
$Result['path'] = array_merge(array(array('url' => erLhcoreClassDesign::baseurl('forum/index'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('forum/edittopic','Forum'))),$topic->path);
$Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('forum/edittopic','Topic edit'));

