<br />
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','New topic')?></h2>

<? if (isset($errArr)) : ?>
<div class="error-list">
<br />
<ul>
<?php foreach ($errArr as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<? endif;?>

<form method="post" action="" enctype="multipart/form-data">

<?php if ($category->has_subcategorys) : ?>
<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Choose topic category');?>:</label>
<select name="TopicCategory" class="default-select">
        <option value="0">-- Please choose --</option>
    <?php foreach ($category->subcategorys as $subcategory) : ?>
        <option value="<?=$subcategory->id?>"><?=htmlspecialchars($subcategory->name)?></option>
    <?php endforeach;?>
</select>
</div>
<?php endif;?>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Topic name');?>:</label>
<input class="inputfield" type="text" name="TopicName" value="<?=htmlspecialchars($topic->topic_name);?>" />
</div>

<?php $bbcodeElementID = '#IDAlbumDescription';?>
<?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Message');?>:</label>
<textarea name="TopicMessage" rows="20" id="IDAlbumDescription" class="default-textarea big-textarea"><?=htmlspecialchars($message->content);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','File');?>:</label>
<input type="file" class="fileinput" name="LocalFile" value="" /> (*.jpg,*.jpeg,*.png,*.gif)
</div>

<input type="submit" class="default-button" name="PublishTopic" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Publish')?>"/>

</form>
<br />
<br />
