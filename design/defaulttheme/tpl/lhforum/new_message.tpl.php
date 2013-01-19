<div id="new-message-block">
<br />
<br />
<br />
<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','New message')?></h1>
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

<form method="post" action="" enctype="multipart/form-data">

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
</div><br />
<br />
