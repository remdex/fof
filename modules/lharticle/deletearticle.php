<?php

$session = erLhcoreClassArticle::getSession();
$Article = $session->load( 'erLhcoreClassModelArticle', $Params['user_parameters']['article_id']);  
$Article->removeThis();

CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');

header('Location: ' . $_SERVER['HTTP_REFERER']);
return;

?>