<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Article edit')?></h1>

<form action="<?=erLhcoreClassDesign::baseurl('article/editstatic')?>/<?=$static->id?>" method="post">

<?php if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : ?>
        <?php $msg = 'Article was updated'?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<?php include_once(erLhcoreClassDesign::designtpl('lharticle/edit_form.tpl.php'));?>
<br />

<ul class="button-group radius">
    <li><input type="submit" class="small button" name="SaveArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Save')?>" /></li>
    <li><input type="submit" class="small button" name="UpdateArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Update')?>" /></li>
    <li><input type="submit" class="small button" name="CancelArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Cancel')?>" /></li>
 </ul>
              
              
</form>
