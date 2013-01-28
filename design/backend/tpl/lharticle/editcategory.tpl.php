<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Category edit');?></h1>

<form action="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$category_new->id?>" method="post">

<?php if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : ?>
        <?php $msg = 'Category was updated'?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>

<?php include(erLhcoreClassDesign::designtpl('lharticle/edit_form_category.tpl.php'));?>

<ul class="button-group radius">
    <li><input type="submit" class="small button" name="SaveArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Save')?>" /></li>
    <li><input type="submit" class="small button" name="UpdateArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Update')?>" /></li>
    <li><input type="submit" class="small button" name="CancelArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Cancel')?>" /></li>
</ul>

</form>