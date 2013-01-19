<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled' ) === true) : ?>
<div class="search-box">
    <form action="<?=erLhcoreClassDesign::baseurl('gallery/search')?>">
    <input type="submit" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search entire gallery')?>" class="default-button" name="doSearch" value="">
    <input type="text" autocomplete="off" rel="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search...')?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Enter keyword or phrase')?>" id="searchtext" class="keywordField" name="SearchText" value="<?=isset($Result['keyword']) ? htmlspecialchars($Result['keyword']) : erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search...')?>" > 
    </form>
</div>
<?php endif; ?>