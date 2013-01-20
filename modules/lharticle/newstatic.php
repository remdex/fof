<?php
$tpl = erLhcoreClassTemplate::getInstance('lharticle/newstatic.tpl.php');

$Static = new erLhcoreClassModelArticleStatic();

if ( isset($_POST['CancelArticle']) ) {        
    erLhcoreClassModule::redirect('article/staticlist');
    exit;
}           
        
if (isset($_POST['SaveArticle']))
{
    $Errors = erLhcoreClassArticle::validateStaticArticle($Static);
    
    if (empty($Errors)) { 
        
        $Static->saveThis();
           
        erLhcoreClassModule::redirect('article/staticlist');
        exit;
        
    } else {
        $tpl->set('errors',$Errors);
    }
}

$tpl->set('static',$Static);
$Result['content'] = $tpl->fetch();

$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/staticlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles')),
array('title' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('article/newstatic','New article'))
)

?>