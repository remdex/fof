<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/newcategory.tpl.php');

$category_id = (int)$Params['user_parameters']['category_id'];
if ( $category_id > 0 ) {
    $category = erLhcoreClassModelArticleCategory::fetch($category_id);
} else {
    $category = new erLhcoreClassModelArticleCategory();
}
$category_new = new erLhcoreClassModelArticleCategory();
$category_new->parent_id = $category_id;

$_SESSION['has_access_to_editor'] = 1;

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/managecategories');
    exit;
}           
        
if (isset($_POST['UpdateArticle']) || isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateCategory($category_new);
    
    if (empty($Errors)) { 
        
        $category_new->saveThis();
        CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');
        
        $urlappend = '';
        
        if ($category_new->parent_id > 0) {
            $urlappend = '/'.$category_new->parent_id;
        }
        
        erLhcoreClassModule::redirect('article/managecategories',$urlappend);
        
        exit;       
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('category_id',$category_id);
$tpl->set('category_parent',$category);
$tpl->set('category_new',$category_new);

$Result['path'] = array();
if ($category_id > 0){
    $Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('article/managecategories/').$category->id,'title' => $category->category_name);
}
$Result['path'][] = array('title' => 'New category');
$Result['content'] = $tpl->fetch();

