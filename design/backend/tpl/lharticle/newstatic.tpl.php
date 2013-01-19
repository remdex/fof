<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/newstatic','New article')?></legend>
<form action="<?=erLhcoreClassDesign::baseurl('article/newstatic')?>" method="post">
  
<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form.tpl.php'));?>
  
<input type="submit" class="default-button" name="UpdateArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/newstatic','Save')?>" />
</form>

</fieldset>