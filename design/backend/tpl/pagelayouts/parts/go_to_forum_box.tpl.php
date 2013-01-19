<div class="right-infobox">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Go to forum topic')?></h3> 
    <input type="text" class="default-input" id="ForumTopicID" value="">
    <input type="button" class="default-button" onclick="document.location = '<?=erLhcoreClassDesign::baseurl('forum/admintopic')?>/'+$('#ForumTopicID').val()" value="OK" />
</div>

<div class="right-infobox">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Go to forum category')?></h3> 
    <input type="text" class="default-input" id="ForumCategoryID" value="">
    <input type="button" class="default-button" onclick="document.location = '<?=erLhcoreClassDesign::baseurl('forum/admincategorys')?>/'+$('#ForumCategoryID').val()" value="OK" />
</div>