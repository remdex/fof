<div class="in-blk">
	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Name');?>*</label>
	<input type="text" class="default-input" name="CategoryName" value="<?=htmlspecialchars($category->name)?>"/>
</div>

<?php $bbcodeElementID = '#IDDescriptionCategory';?>
<?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>
<div class="in-blk">
	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Description');?>*</label>
	<textarea name="DescriptionCategory" id="IDDescriptionCategory" class="default-textarea big-textarea"><?=htmlspecialchars($category->description)?></textarea>
</div>
		
<div class="in-blk">
	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Owner');?>*</label>
	<a href="<?=erLhcoreClassDesign::baseurl('/user/edit')?>/<?=$category->user_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Owner');?></a><br />
	<input type="text" class="default-input" name="UserID" value="<?=$category->user_id?>" />	
</div>