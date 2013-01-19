<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationedit','Image variation edit')?></legend>

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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('shop/imagevariationedit')?>/<?=$imagevariation->id?>">

<?php include_once(erLhcoreClassDesign::designtpl('lhshop/imagevariation_form.tpl.php'));?>

<input type="submit" class="default-button" name="UpdateVariation" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationedit','Update')?>"/>

</form>

</fieldset>