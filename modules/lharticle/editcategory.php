<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/editcategory.tpl.php');

$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);  

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/managecategories');
    exit;
}           
        
if (isset($_POST['UpdateArticle']) || isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateCategory($Category);
    
    if (empty($Errors)) { 
        
        $Category->saveThis();
        CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');        
        if (isset($_POST['SaveArticle'])) {        
            erLhcoreClassModule::redirect('article/managecategories');
            exit;
        } else {
            $tpl->set('updated',true);
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}





$tpl->set('category',$Category);
$Result['content'] = $tpl->fetch();


$Result['path'] = array(array('title' => $Category->category_name));


?>