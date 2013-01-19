<?php
     
$filterArray = array();
         
$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/admintopic.tpl.php');
try {
$Album = erLhcoreClassModelForumTopic::fetch((int)$Params['user_parameters']['topic_id']); 
} catch (Exception $e){
    erLhcoreClassModule::redirect('/');
    exit;
}

$pages = new lhPaginator();
$pages->items_total = erLhcoreClassModelForumMessage::getCount(array('disable_sql_cache' => true,'filter' => array('topic_id' => $Album->id)));
$pages->serverURL = erLhcoreClassDesign::baseurl('forum/admintopic').'/'.$Album->id;
$pages->setItemsPerPage((int)erLhcoreClassModelSystemConfig::fetch('posts_per_page')->current_value);
$pages->paginate();

$tpl->set('pages',$pages);
$tpl->set('topic',$Album);

$Result['content'] = $tpl->fetch();

$pathObjects = array();
erLhcoreClassModelForumCategory::calculatePathObjects($pathObjects,$Album->category);   
 
$pathCategorys = array();    
$path = array()  ;
$path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/category','Root category'));

foreach ($pathObjects as $pathItem)
{
   $path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$pathItem->id,'title' => $pathItem->name);
   $pathCategorys[] = $pathItem->id; 
}

$Result['path'] = $path;
$Result['left_menu'] = 'forum';