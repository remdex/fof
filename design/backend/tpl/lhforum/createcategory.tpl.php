<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','New category');?></h1></div>

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>

<form action="<?=erLhcoreClassDesign::baseurl('forum/createcategory')?>/<?=isset($category_parent) ? $category_parent->id : ''?>" method="post">
			
<?php include_once(erLhcoreClassDesign::designtpl('lhforum/editcategory_form.tpl.php'));?>

<input type="submit" class="default-button" name="Update_Category" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','Save');?>"/> 
			
<?php if (isset($category_parent)) : ?>			
&laquo; <a href="<?=erLhcoreClassDesign::baseurl('forum/admincategorys')?>/<?=$category_parent->cid?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createcategory','back')?></a>
<?endif;?>					
	
</form>


