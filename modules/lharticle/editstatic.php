<?php
$tpl = erLhcoreClassTemplate::getInstance('lharticle/editstatic.tpl.php');

$Static = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleStatic', (int)$Params['user_parameters']['static_id']);  

$_SESSION['has_access_to_editor'] = 1;

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/staticlist');
    exit;
}           
        
if (isset($_POST['UpdateArticle']) || isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateStaticArticle($Static);
    
    if (empty($Errors)) { 
        
        $Static->saveThis();
        CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');  
                
        if (isset($_POST['SaveArticle'])) {        
            erLhcoreClassModule::redirect('article/staticlist');
            exit;
        } else {
            $tpl->set('updated',true);
        }
        
    } else {
        $tpl->set('errors',$Errors);
    }
}


$tpl->set('static',$Static);
$Result['content'] = $tpl->fetch();

$Result['path'] = array(array('title' => $Static->name));

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/staticlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles')),
array('title' =>  $Static->name)
)

?>