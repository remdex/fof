<?php
$cache = CSCacheAPC::getMem(); 
$cacheKey = md5('index_page_forum_'.$cache->getCacheVersion('forum_category_0').'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_version_'.$cache->getCacheVersion('site_version'));

if (($Result = $cache->restore($cacheKey)) === false)
{ 
    $tpl = erLhcoreClassTemplate::getInstance( 'lhforum/index.tpl.php');
    $Result['content'] = $tpl->fetch();
    $Result['path'] = array();   
    $cache->store($cacheKey,$Result);
}

?>