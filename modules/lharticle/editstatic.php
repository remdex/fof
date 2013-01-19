<?php
$tpl = erLhcoreClassTemplate::getInstance('lharticle/editstatic.tpl.php');

$Static = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleStatic', (int)$Params['user_parameters']['static_id']);  

if (isset($_POST['UpdateArticle']))
{
    $Static->content = $_POST['ArticleBody'];
    $Static->name = $_POST['ArticleName'];    
    $Static->saveThis();
    	
    erLhcoreClassModule::redirect('article/staticlist');
    return; 
}

$tpl->set('static',$Static);
$Result['content'] = $tpl->fetch();

$Result['path'] = array(array('title' => $Static->name));


$Result['path'] = array(
array('url' => erLhcoreClassDesign::baseurl('article/staticlist'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('article/staticlist','Static articles')),
array('title' =>  $Static->name)
)

?>