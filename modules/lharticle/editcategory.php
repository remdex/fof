<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/editcategory.tpl.php');

$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);  

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/managecategories');
    exit;
}           

$_SESSION['has_access_to_editor'] = 1;

if (isset($_POST['UpdateArticle']) || isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateCategory($Category);
    
    if (empty($Errors)) { 
        
        $Category->saveThis();
        CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');        
        if (isset($_POST['SaveArticle'])) { 
              
            $append = '';     
            if ($Category->parent_id > 0) {
                $append = '/'.$Category->id;
            }
            
            erLhcoreClassModule::redirect('article/managecategories',$append);
            exit;
        } else {
            $tpl->set('updated',true);
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('category_new',$Category);
$Result['content'] = $tpl->fetch();

$Result['path'] = array();
$Result['path'][] = array('url' => erLhcoreClassDesign::baseurl('article/managecategories').'/'.$Category->id, 'title' => $Category->category_name);
$Result['path'][] = array('title' => 'Edit category');


?>