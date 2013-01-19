<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','New album')?></h1>
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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('gallery/createalbumadmin')?>/<?=$album->category?>">

<?php include_once(erLhcoreClassDesign::designtpl('lhgallery/albumeditadmin_form.tpl.php'));?>

<input type="submit" class="default-button" name="CreateAlbum" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/createalbumadmin','Save')?>"/>

</form>

