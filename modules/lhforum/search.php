<?php

$definition = array(
'SearchText' => new ezcInputFormDefinitionElement(
    ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
)
);
$form = new ezcInputForm( INPUT_GET, $definition );
    
$searchParams = array('SearchLimit' => 25,'keyword' => '');
$userParams ='';

if ( $form->hasValidData( 'SearchText' ) && trim($form->SearchText) != '')
{
    $searchParams['keyword'] = trim(str_replace('+',' ',$form->SearchText));
    $userParams .= '/(keyword)/'.urlencode(trim($form->SearchText));
} elseif ($Params['user_parameters_unordered']['keyword'] != '') {

   // We have to reencode because ngnix or php-fpm somewhere wrongly parses it. 
   $keywordDecoded =  trim(str_replace('+',' ',urldecode($Params['user_parameters_unordered']['keyword'])));
   $userParams .= '/(keyword)/'.urlencode($keywordDecoded);
   $searchParams['keyword'] = $keywordDecoded;
}
  
$searchParams['sort'] = '@relevance DESC, @id DESC';
$searchParams['SearchLimit'] = (int)erLhcoreClassModelSystemConfig::fetch('posts_per_page')->current_value;
$searchParams['SearchOffset'] = 0;

$cache = CSCacheAPC::getMem();        
$cacheKey =  md5('SphinxForumSearchPage_VersionCache'.$cache->getCacheVersion('sphinx_forum_cache_version').erLhcoreClassGallery::multi_implode(',',$searchParams).'page_'.$Params['user_parameters_unordered']['page'].'_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess);

if (($Result = $cache->restore($cacheKey)) === false)
{
    $tpl = erLhcoreClassTemplate::getInstance( 'lhforum/search.tpl.php');
    
    if ($searchParams['keyword'] != '')
    {     
        $tpl->set('enter_keyword',false);
        $pages = new lhPaginator();
                  
        $searchParams['SearchOffset'] = $pages->low;
        $searchResult = erLhcoreClassForum::searchSphinx($searchParams,false);

        $pages->items_total = $searchResult['total_found'];
        $pages->serverURL = erLhcoreClassDesign::baseurl('forum/search').$userParams;
        $pages->paginate();        
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('forum/search').$userParams.($pages->current_page > 1 ? '/(page)/'.$pages->current_page : '');        
               
        $tpl->setArray ( array (
                'pages'             => $pages,
                'items'             => $searchResult['list'],
                'keyword'           => $searchParams['keyword']               
        ) );              

        $Result['content'] = $tpl->fetch();
        $Result['path'] = array(array('url' =>erLhcoreClassDesign::baseurl('gallery/search'), 'title' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/searchrss','Search results').' - '.$searchParams['keyword']))   ;
        $Result['title_path'] = array(array('title' => $searchParams['keyword'].' &laquo; '.erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','search results')));

        if ($Params['user_parameters_unordered']['page'] > 1) {        
            $Result['path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
            $Result['title_path'][] = array('title' => erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Page').' - '.(int)$Params['user_parameters_unordered']['page']); 
        }

        $cache->store($cacheKey,$Result,12000);
    } else {
        $Result['path_base'] = erLhcoreClassDesign::baseurldirect('forum/search');
        $tpl->set('enter_keyword',true);
        $Result['path'] = array(array('url' =>erLhcoreClassDesign::baseurl('forum/search'), 'title' =>  erTranslationClassLhTranslation::getInstance()->getTranslation('forum/searchrss','Search')));
        $Result['content'] = $tpl->fetch();
        $cache->store($cacheKey,$Result,12000);
    }
}


?>