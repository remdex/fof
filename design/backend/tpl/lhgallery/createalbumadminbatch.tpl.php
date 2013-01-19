<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','New batch album create')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('/gallery/createalbumadminbatch')?>/<?=$categoryID?>">

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Paste album names separated by new line');?></label>
<textarea name="AlbumName" style="width:100%;height:400px;"></textarea>
</div>

<div class="in-blk">
<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Public');?></label>
<input type="checkbox" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','All users can uploads images to this album');?>" value="on" <?=$album->public == 1 ? 'checked="checked"' : ''?> name="AlbumPublic" />
</div>

<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Save')?>"/>

</form>

