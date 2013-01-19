<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Edit topic')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('forum/edittopic')?>/<?=$topic->id?>" enctype="multipart/form-data">

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Topic name');?>:</label>
<input class="inputfield" type="text" name="TopicName" value="<?=htmlspecialchars($topic->topic_name);?>" />
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Topic status');?>:</label>
<select name="TopicStatus">
    <option value="0"<?=$topic->topic_status == 0 ? ' selected="selected"' : ''?>>ACTIVE</option>
    <option value="1"<?=$topic->topic_status == 1 ? ' selected="selected"' : ''?>>LOCKED</option>
</select>
</div>

<input type="submit" class="default-button" name="UpdateTopic" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Update')?>"/>

</form>

