<?php
$tpl = new erLhcoreClassTemplate( 'lharticle/static.tpl.php');


$Static = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleStatic', (int)$Params['user_parameters']['static_id']);  

$tpl->set('static',$Static);

$Result['content'] = $tpl->fetch();


$Result['path'] = array(array('title' => $Static->name))



?>