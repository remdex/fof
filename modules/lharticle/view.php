<?php
$tpl = erLhcoreClassTemplate::getInstance( 'lharticle/view.tpl.php');

$Article = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticle', (int)$Params['user_parameters']['article_id']);  
$tpl->set('article',$Article);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(array('title' => $Article->article_name));

?>