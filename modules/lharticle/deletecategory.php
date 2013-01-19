<?php

$Category = erLhcoreClassArticle::getSession()->load( 'erLhcoreClassModelArticleCategory', (int)$Params['user_parameters']['category_id']);  
$Category->removeThis();

CSCacheAPC::getMem()->increaseCacheVersion('article_cache_version');

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
