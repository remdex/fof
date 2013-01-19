<?php if (erConfigClassLhConfig::getInstance()->getSetting( 'sphinx', 'enabled' ) == true) : ?>
<div class="right forum-search-box">
<form action="<?=erLhcoreClassDesign::baseurl('forum/search')?>">
<input type="text" class="inputfield" autocomplete="off" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Enter keyword or phrase')?>" class="keywordField" name="SearchText" value="<?=isset($keyword) ? htmlspecialchars($keyword) : ''?>" >
<input type="submit" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search entire forum')?>" class="default-button" name="doSearch" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search')?>">
</form>
</div>
<?php endif;?>