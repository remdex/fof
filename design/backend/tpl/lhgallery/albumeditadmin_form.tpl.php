<table>
<tr>
	<td>
		<div class="in-blk">
			<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album name');?> *</label>
			<input class="default-input" type="text" name="AlbumName" value="<?=htmlspecialchars($album->title);?>" />
		</div>
	</td>
	<td>
		<div class="in-blk">
			<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album category');?> *</label>
			<select name="AlbumCategoryID" class="default-select">
				<?php foreach (erLhcoreClassModelGalleryCategory::getParentCategories(array('disable_sql_cache' => true,'use_iterator' => true,'limit' => 1000000)) as $category) : ?>
					<option value="<?=$category->cid?>" <?=$category->cid == $album->category ? 'selected="selected"' : ''?>><?=htmlspecialchars($category->name)?></option>
				<?php endforeach;?>
			</select>
		</div>
	</td>
	<td>
		<div class="in-blk">
			<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album owner');?> *</label>			
			<select name="UserID" class="default-select">
				<?php foreach (erLhcoreClassUser::getUserList() as $user) : ?>
					<option value="<?=$user['id']?>" <?=$user['id'] == $album->owner_id ? 'selected="selected"' : ''?>><?=htmlspecialchars($user['username'])?></option>
				<?php endforeach;?>
			</select>				
		</div>
	</td>
</tr>
</table>


<?php $bbcodeElementID = '#IDAlbumDescription';?>
<?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Description');?></label>
<textarea name="AlbumDescription" id="IDAlbumDescription" class="default-textarea big-textarea"><?=htmlspecialchars($album->description);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Keywords');?></label>
<input class="default-input" type="text" name="AlbumKeywords" value="<?=htmlspecialchars($album->keyword);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Public');?></label>
<input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','All users can uploads images to this album');?>" value="on" <?=$album->public == 1 ? 'checked="checked"' : ''?> name="AlbumPublic" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Hidden');?></label>
<input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Hide album from users');?>" value="on" <?=$album->hidden == 1 ? 'checked="checked"' : ''?> name="AlbumHidden" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album thumbnail');?></label>
<?php if ($album->album_pid == 0) : ?>
    <?php if ($album->album_thumb_path !== false) :?> 
    <img src="<?=erLhcoreClassDesign::imagePath($album->album_thumb_path)?>" alt="" width="130" height="140">
    <?php else :?>
    <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140">            
    <?php endif;?><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Newest album image');?>.
<?php else : ?>
    <?php if ($album->album_thumb_path !== false) :?> 
    <img src="<?=erLhcoreClassDesign::imagePath($album->album_thumb_path)?>" alt="" width="130" height="140">
    <?php else :?>
    <img src="<?=erLhcoreClassDesign::design('images/newdesign/nophoto.jpg')?>" alt="" width="130" height="140">            
    <?php endif;?>Assigned image. <a title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Revert to newest album image display mode')?>" href="<?=erLhcoreClassDesign::baseurl('gallery/albumeditadmin')?>/<?=$album->aid?>/(action)/removethumb"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Remove')?></a>
<?php endif;?>
</div>