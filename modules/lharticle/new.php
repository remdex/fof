<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/new.tpl.php');

$Article = new erLhcoreClassModelArticle();
$Category = erLhcoreClassModelArticleCategory::fetch($Params['user_parameters']['category_id']);  
$Article->category_id = $Params['user_parameters']['category_id'];
$Article->publishtime = time(); 

$_SESSION['has_access_to_editor'] = 1;

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/managecategories','/'.$Article->category_id);
    exit;
} 

if (isset($_POST['UpdateArticle']) || isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateArticle($Article);
    
    if (empty($Errors)) { 
        
        $Article->saveThis();
                
        if (isset($_POST['SaveArticle'])) {        
            erLhcoreClassModule::redirect('article/managecategories','/'.$Article->category_id);
            exit;
        } else {
            $tpl->set('updated',true);
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('category_id',$Params['user_parameters']['category_id']);
$tpl->set('article',$Article);


$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/managecategories/').$Category->id,'title' => $Category->category_name),
array('title' => 'New article')
);

$Result['content'] = $tpl->fetch();

