<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/editcategory','Category edit');?> - <?php echo htmlspecialchars($category->name)?></h1></div>

<? if (isset($errArr)) : ?>
    <? foreach ((array)$errArr as $error) : ?>
    	<div class="error">*&nbsp;<?=$error;?></div>
    <? endforeach; ?>
<? endif;?>
		
<form action="<?=erLhcoreClassDesign::baseurl('forum/editcategory')?>/<?=$category->id?>" method="post">

<?php include_once(erLhcoreClassDesign::designtpl('lhforum/editcategory_form.tpl.php'));?> 
			
<input type="submit" class="default-button" name="Update_Category" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('forum/editcategory','Update');?>"/> &laquo; <a href="<?=erLhcoreClassDesign::baseurl('forum/admincategorys')?>/<?=$category->parent?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','back')?></a>
				
</form>
				
