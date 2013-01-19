<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhforum/admincategorys.tpl.php');

if (isset($_POST['UpdatePriority'])) {
    
    foreach ($_POST['CategoryIDs'] as $key => $categoryID) {
        $category = erLhcoreClassModelForumCategory::fetch($categoryID);
        
        if ($category->placement != $_POST['Position'][$key]) {
           $category->clearCategoryCache();
        };
        
        $category->placement = $_POST['Position'][$key];
        erLhcoreClassForum::getSession()->update( $category );
    }
}

$pathCategorys = array();
$path = array(); 
$path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/admincategorys','Root category')); 

if (is_numeric($Params['user_parameters']['category_id']) && $Params['user_parameters']['category_id'] > 0)
{
    $Category = erLhcoreClassModelForumCategory::fetch($Params['user_parameters']['category_id']);
    $tpl->set('category',$Category);
    
    
    $pages = new lhPaginator();
    $pages->items_total = erLhcoreClassModelForumTopic::getCount(array('filter' => array('path_'.$Category->depth => $Category->id)));
    $pages->setItemsPerPage(16);
    $pages->serverURL = erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$Category->id;
    $pages->paginate();
    
    $tpl->set('pagesCurrent',$pages);
    
    
    
    $pathObjects = array();
    erLhcoreClassModelForumCategory::calculatePathObjects($pathObjects,$Category->id);  
         
    foreach ($pathObjects as $pathItem)
    {
       $pathCategorys[] = $pathItem->cid; 
       $path[] = array('url' => erLhcoreClassDesign::baseurl('forum/admincategorys').'/'.$pathItem->id,'title' => $pathItem->name); 
    }
    
} else {    
    $tpl->set('category',false);     
}

$Result['content'] = $tpl->fetch();
$Result['path'] = $path;
$Result['left_menu'] = 'forum';