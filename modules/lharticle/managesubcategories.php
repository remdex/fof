<?php

$tpl = erLhcoreClassTemplate::getInstance('lharticle/managesubcategories.tpl.php');
$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);  

if (isset($_POST['CreateCategory']))
{
    $CategoryNew = new erLhcoreClassModelArticleCategory();        
    $CategoryNew->category_name = $_POST['CategoryName'];
    $CategoryNew->position = $_POST['CategoryPos'];
    $CategoryNew->parent = $Category->id;                    
    erLhcoreClassArticle::getSession()->save($CategoryNew); 
    
    CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');
}

$tpl->set('category',$Category);
$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => $Category->category_name));


$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('user/edit','System configuration')),

array('url' => erLhcoreClassDesign::baseurl('article/managecategories/').$Category->id,'title' => $Category->category_name)


)


?>