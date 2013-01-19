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
	<a href="<?=erLhcoreClassDesign::baseurl('/user/edit')?>/<?=$category->owner_id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Owner');?></a><br />
	<select name="UserID" class="default-select">
		<?php foreach (erLhcoreClassUser::getUserList() as $user) : ?>
			<option value="<?=$user['id']?>" <?=$user['id'] == $category->owner_id ? 'selected="selected"' : ''?>><?=htmlspecialchars($user['username'])?></option>
		<?php endforeach;?>
	</select>
</div>
		
<div class="in-blk">
	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/editcategory','Hide subcategorys in frontpage');?>*</label>
	<input name="HideFrontpage" type="checkbox" value="on" <?=$category->hide_frontpage == 1 ? 'checked="checked"' : ''?> />
</div>