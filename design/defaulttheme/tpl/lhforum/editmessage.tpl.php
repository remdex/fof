<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Edit message')?></h1>
</div>

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
<br />

<form method="post" action="<?=erLhcoreClassDesign::baseurl('forum/editmessage')?>/<?=$message->id?>" enctype="multipart/form-data">
<?php $bbcodeElementID = '#IDAlbumDescription';?>
<?php include(erLhcoreClassDesign::designtpl('lhbbcode/bbcode_js_css.tpl.php'));?>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Message');?>:</label>
<textarea name="TopicMessage" rows="20" id="IDAlbumDescription" class="default-textarea big-textarea"><?=htmlspecialchars($message->content);?></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','File');?>:</label>
<input type="file" name="LocalFile" value="" /> (*.jpg,*.jpeg,*.png,*.gif)
</div>

<input type="submit" class="default-button" name="UpdateMessage" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Update')?>"/>

</form>

