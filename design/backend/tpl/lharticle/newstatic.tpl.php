<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/newstatic','New article')?></h1>
<form action="<?=erLhcoreClassDesign::baseurl('article/newstatic')?>" method="post">

<?php if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form.tpl.php'));?><br />

<ul class="button-group radius">
    <li><input type="submit" class="small button" name="SaveArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Save')?>" /></li>
    <li><input type="submit" class="small button" name="CancelArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Cancel')?>" /></li>
 </ul>
 
</form>

