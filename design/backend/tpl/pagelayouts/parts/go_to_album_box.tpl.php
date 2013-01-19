<div class="right-infobox">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Go to album')?></h3> 
    <input type="text" class="default-input" id="AlbumID" value="">
    <input type="button" class="default-button" onclick="document.location = '<?=erLhcoreClassDesign::baseurl('gallery/managealbumimages')?>/'+$('#AlbumID').val()" value="OK" />
</div>