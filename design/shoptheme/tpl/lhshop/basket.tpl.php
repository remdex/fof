<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Basket');?></h1>
</div>
<? if (isset($error)) : ?><h2 class="error-h2"><?=$error;?></h2><? endif;?>
<br />


<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Image name');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Version');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Image size');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Format');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Price');?></th>
	<th></th>
</tr>
<?php foreach ($basketItems as $item) : ?>
	<tr id="row_basket-<?=$item->pid?>-<?=$item->variation->id?>">
		<td><a href="<?=erLhcoreClassDesign::baseurl('gallery/image')?>/<?=$item->image->pid?>"><?=htmlspecialchars($item->image->name_user)?></a></td>
		<td><?=$item->variation->name?></td>
		<td><?=$item->variation->getSize($item->image)?></td>
		<td><?=$item->variation->getFormat($item->image)?></td>
		<td><?=$item->variation->credits?> (<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Credits');?>)</td>
		<td><a rel="<?=$item->pid?>-<?=$item->variation->id?>" class="ad-basket ad-basket-ok" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Remove from basket');?>" ></a></td>
	</tr>
<?php endforeach;?>
</table>
<br />

<script type="text/javascript">
$('.ad-basket-ok').click(function() {
	var params = $(this).attr('rel').split('-');
	hw.deleteFromBasketRow(params[0],params[1]);
	return false;
});
</script>

<?php
$currentUser = erLhcoreClassUser::instance();                       
if ($currentUser->isLogged()) : ?>

<?php if (count($basketItems) > 0) : ?>
<a href="<?=erLhcoreClassDesign::baseurl('/shop/paymentoption')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','Choose payment option');?> &raquo;</a>
<?php else :?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','In order to checkout atlest one item have to be selected.');?>
<?php endif;?>

<?php else :?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','In order to checkout you have to');?> <a href="<?=erLhcoreClassDesign::baseurl('/user/registration')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','sign in');?></a> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','or');?> <a href="<?=erLhcoreClassDesign::baseurl('/user/login')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','login');?></a> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/basket','if you have already created an account');?>
<?php endif;?>


