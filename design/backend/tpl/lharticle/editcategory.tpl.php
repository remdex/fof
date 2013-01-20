<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Category edit');?></h1>

<form action="<?=erLhcoreClassDesign::baseurl('article/editcategory')?>/<?=$category->id?>" method="post">

<?php if (isset($errors)) : ?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
<?php endif; ?>

<?php if (isset($updated)) : ?>
        <?php $msg = 'Category was updated'?>
		<?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
<?php endif; ?>


<label>Name</label>
<input type="text" class="inputfield" name="CategoryName" value="<?=htmlspecialchars($category->category_name)?>" />

<label>Intro</label>
<?
$oFCKeditor = new CKEditor() ;        
$oFCKeditor->basePath = erLhcoreClassDesign::design('js/ckeditor').'/' ;  
CKFinder::SetupCKEditor($oFCKeditor, erLhcoreClassDesign::design('js/ckfinder/')); 
$oFCKeditor->config['height'] = 300;
$oFCKeditor->config['width'] = '100%';
$oFCKeditor->editor('Intro',$category->intro) ;
?>
<br />

<label>Position</label>
<input type="text" class="inputfield" name="CategoryPos" value="<?=htmlspecialchars($category->placement)?>" />

<ul class="button-group radius">
    <li><input type="submit" class="small button" name="SaveArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Save')?>" /></li>
    <li><input type="submit" class="small button" name="UpdateArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Update')?>" /></li>
    <li><input type="submit" class="small button" name="CancelArticle" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('article/editstatic','Cancel')?>" /></li>
 </ul>
 
 
</form>