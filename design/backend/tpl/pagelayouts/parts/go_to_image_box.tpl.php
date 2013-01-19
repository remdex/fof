<div class="right-infobox">
<h3><?=erTranslationClassLhTranslation::getInstance()->getTranslation('pagelayout/pagelayout','Go to image')?></h3> 
    <input type="text" class="default-input" id="imageEditPID" value="">
    <input type="button" class="default-button" onclick="document.location = '<?=erLhcoreClassDesign::baseurl('gallery/editimage')?>/'+$('#imageEditPID').val()" value="OK" />
</div>