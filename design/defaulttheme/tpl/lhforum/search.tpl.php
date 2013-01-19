<?php if ($enter_keyword === true) : ?>
<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Enter keyword or phrase')?></h1>
</div>

<form action="<?=erLhcoreClassDesign::baseurl('forum/search')?>">
<input type="text" class="inputfield" autocomplete="off" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Enter keyword or phrase')?>" class="keywordField" name="SearchText" value="" >
<input type="submit" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search entire forum')?>" class="default-button" name="doSearch" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Search')?>">
</form>

<?php else : ?>

<div class="header-list">
<?php include(erLhcoreClassDesign::designtpl('lhforum/search_box.tpl.php'));?> 
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Search results')?> - <?=htmlspecialchars($keyword)?></h1>
</div>

<? if ($pages->items_total > 0) { ?>

  <?php include_once(erLhcoreClassDesign::designtpl('lhforum/message_search_list.tpl.php'));?> 
  
<? } else { ?>

<p><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/search','Nothing found')?>...</p>

<? } ?>

<?php endif;?>