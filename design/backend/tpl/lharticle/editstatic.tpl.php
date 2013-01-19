<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Article edit')?></h1>

<form action="<?=erLhcoreClassDesign::baseurl('article/editstatic')?>/<?=$static->id?>" method="post">
  
<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form.tpl.php'));?>
  <br />

<input type="submit" class="small button" name="UpdateArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Update')?>" />
</form>
