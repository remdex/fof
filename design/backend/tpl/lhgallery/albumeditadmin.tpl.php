<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Album edit')?></h1>
</div>

<? if (isset($errArr)) : ?>
<div class="error-list">
<ul>
<?php foreach ($errArr as $err) : ?>
    <li><?=$err?></li>
<?php endforeach;?>
</ul>
</div>
<br />
<? endif;?>


<form method="post" action="<?=erLhcoreClassDesign::baseurl('gallery/albumeditadmin')?>/<?=$album->aid?>">

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/albumeditadmin_form.tpl.php'));?>

<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/albumedit','Update')?>"/>

</form>

