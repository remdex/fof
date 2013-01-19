<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Article edit')?></legend>

<form action="<?=erLhcoreClassDesign::baseurl('article/editstatic')?>/<?=$static->id?>" method="post">
  
<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form.tpl.php'));?>
  
<input type="submit" class="default-button" name="UpdateArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Update')?>" />
</form>
</fieldset>