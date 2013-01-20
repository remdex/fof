<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/editarticle.tpl.php');

$Article = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticle', (int)$Params['user_parameters']['article_id']);  
$Category = erLhcoreClassModelArticleCategory::getCategoryByID($Article->category_id); 

$_SESSION['has_access_to_editor'] = 1;

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/managesubcategories','/'.$Article->category_id);
    exit;
} 

if (isset($_POST['UpdateArticle']) || isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateArticle($Article);
    
    if (empty($Errors)) { 
        
        $Article->saveThis();
        CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');  
                
        if (isset($_POST['SaveArticle'])) {        
            erLhcoreClassModule::redirect('article/managesubcategories','/'.$Article->category_id);
            exit;
        } else {
            $tpl->set('updated',true);
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('article',$Article);
$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/managesubcategories').'/'.$Category->id,'title' => $Category->category_name),
array('title' => $Article->article_name)
);


?>