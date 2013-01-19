<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled' ) === true) : ?>
<div class="left-infobox search-infobox">				
	<h3><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastsearches')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last searches')?> &raquo;</a></h3>
	<ul>
<?php 
$cache = CSCacheAPC::getMem();
if (($resultLastSearchTplBlock = $cache->restore('last_search_tpl_block_siteaccess'.erLhcoreClassSystem::instance()->SiteAccess)) === false) :
ob_start();
?>

	<?php foreach (erLhcoreClassModelGalleryLastSearch::getSearches() as $search) : ?>									
	   <li>
	   <a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>/(keyword)/<?=urlencode($search->keyword);?>">
	   <span class="cnt">(<?=$search->countresult;?>)</span>
	   <span><?=htmlspecialchars($search->keyword);?></span>
	   </a>					  
	<?endforeach;?>

<?php 
$resultLastSearchTplBlock = ob_get_clean();
$cache->store( 'last_search_tpl_block_siteaccess'.erLhcoreClassSystem::instance()->SiteAccess, $resultLastSearchTplBlock, 60 ); //Cache for 60 seconds
endif;
echo $resultLastSearchTplBlock; ?>
	</ul>
</div>
<?php
endif; ?>