<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/editcategory.tpl.php');

$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);  

if (isset($_POST['UpdateCategory']))
{
    $Category->placement = $_POST['CategoryPos'];
    $Category->category_name = $_POST['CategoryName'];
    $Category->intro = $_POST['Intro'];
        
    erLhcoreClassArticle::getSession()->update($Category);
    
    CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');
    
    erLhcoreClassModule::redirect('article/managecategories');
    return; 
}


$tpl->set('category',$Category);
$Result['content'] = $tpl->fetch();


$Result['path'] = array(array('title' => $Category->category_name));


?>