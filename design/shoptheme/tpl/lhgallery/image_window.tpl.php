<div class="img float-break">
<div class="left-photo">
<img src="<?=($image->pwidth < 450) ? erLhcoreClassDesign::imagePath($image->filepath.urlencode($image->filename)) : erLhcoreClassDesign::imagePath($image->filepath.'normal_'.urlencode($image->filename))?>" alt="<?=htmlspecialchars($image->name_user);?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Click to see fullsize')?>" >
</div>
<div class="shop-options">
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Version')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Image size')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Format')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Price')?></th>
	<th></th>
</tr>


<?php 

$basketItems = erLhcoreClassModelShopBasketImage::getImages(array('disable_sql_cache' => true,'filter' => array('pid' => $image->pid)));
$selectedItemsCombinations = array();
foreach ($basketItems as $item)
{
	$selectedItemsCombinations[] = $item->variation_id;
}

foreach (erLhcoreClassModelShopImageVariation::getImageVariation() as $variation) : 
$selected = in_array($variation->id,$selectedItemsCombinations);
?> 
<tr>
	<td><?=$variation->name?></td>
	<td><?=$variation->getSize($image)?></td>
	<td>.<?=$variation->getFormat($image)?></td>
	<td><?=$variation->credits?> (<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','credits')?>)</td>
	<td><a href="#" id="basket-<?=$image->pid?>-<?=$variation->id?>" rel="<?=$variation->id?>" class="ad-basket <?= $selected ? 'ad-basket-ok' : 'add-to-cart'?>" <?php if ($selected == true) : ?> title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Remove from basket')?>" <?php else : ?> title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/image','Add to basket')?>" <?endif;?> ></a></td>
</tr>
<?php endforeach;?>
</table>

<script>

$('.add-to-cart').click(function() {
  hw.addToBasket(<?=$image->pid?>,$(this).attr('rel'));
  return false;
});

$('.ad-basket-ok').click(function() {
  hw.deleteFromBasket(<?=$image->pid?>,$(this).attr('rel'));
  return false;
})

</script>
</div>

<?php if( $image->caption != '') : ?>
<div class="float-break"><?=htmlspecialchars($image->caption)?></div>
<?endif;?>
</div>