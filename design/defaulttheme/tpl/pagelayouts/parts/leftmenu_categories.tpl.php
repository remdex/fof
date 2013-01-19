<div class="left-infobox">				
		
		<ul>													
		<?php 		
		$cache = CSCacheAPC::getMem();		
		$cacheKey = md5('leftmenu_categories_tpl_categories_siteaccess_'.erLhcoreClassSystem::instance()->SiteAccess.'_category_0_version'.$cache->getCacheVersion('category_0'));
        if (($resultLeftMenuCategoriesBlock = $cache->restore($cacheKey)) === false) :
        ob_start(); ?>        
        <h3><a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','View all categorys')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/rootcategory')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Root category')?> (<?=erLhcoreClassModelGalleryCategory::fetchCategoryColumn(array('filter' => array('parent' => 0)))?>) &raquo;</a></h3>
        <? foreach (erLhcoreClassModelGalleryCategory::getParentCategories(array('limit' => 5,'filter' => array('parent' => 0),'cache_key' => 'version_'.$cache->getCacheVersion('category_0'))) as $category) : ?>
		    <li><a href="<?=$category->path_url?>"><?=htmlspecialchars($category->name)?></a>  
        <?php endforeach; 
        $resultLeftMenuCategoriesBlock = ob_get_clean();   
        $cache->store($cacheKey,$resultLeftMenuCategoriesBlock);
        endif;
        echo $resultLeftMenuCategoriesBlock;
        ?>
		</ul>									
</div>


<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','All time')?></h3>
		<ul>													
		    <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/popular')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/toprated')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a>                  
		</ul>									
</div>

<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Interactive')?></h3>
		<ul>           
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploads')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads');?></a>  
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lasthits')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last hits');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastcommented')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last commented');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastrated')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last rated');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/lastuploadstoalbums')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last uploads to albums');?></a>                 
		</ul>									
</div>

<div class="left-infobox">				
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Last 24 h.')?></h3>
		<ul>  
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/popularrecent')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Most popular');?></a>                 
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/ratedrecent')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Top rated');?></a>                 
		</ul>									
</div>

<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled' ) === true || erConfigClassLhConfig::getInstance()->getSetting( 'color_search', 'search_enabled' ) === true) : ?>
<div class="left-infobox">
		<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search')?></h3>
		<ul>
		    <?php if (erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled' ) === true) : ?> 
		    <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/search')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search by keyword');?></a>  
		    <?php endif;?>
		    
		    <?php if (erConfigClassLhConfig::getInstance()->getSetting( 'color_search', 'search_enabled' ) === true) : ?>               
            <li><a href="<?=erLhcoreClassDesign::baseurl('gallery/color')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search by color');?></a> 
            <?php endif;?>
            
		    <?php if (erConfigClassLhConfig::getInstance()->getSetting( 'imgseek', 'enabled' ) === true) : ?>               
            <li><a href="<?=erLhcoreClassDesign::baseurl('similar/image')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search by similarity')?></a></li>
            <li><a href="<?=erLhcoreClassDesign::baseurl('similar/sketch')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search by sketch')?></a></li>
            <?php endif;?>
            
		</ul>
</div>
<?php endif;?>