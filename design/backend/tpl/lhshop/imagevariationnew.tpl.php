<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationnew','New image variation')?></legend>

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

<form method="post" action="<?=erLhcoreClassDesign::baseurl('shop/imagevariationnew')?>">

<?php include_once(erLhcoreClassDesign::designtpl('lhshop/imagevariation_form.tpl.php'));?>

<input type="submit" class="default-button" name="SaveVariation" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariationnew','Save')?>"/>

</form>

</fieldset>