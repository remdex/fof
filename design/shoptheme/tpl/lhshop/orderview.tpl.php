<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Order view')?></h1>
</div>


<table>
	<tr>
		<td>ID</td>
		<td><?=$order->id?></td>
	</tr>	
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Order time')?></td>
		<td><?=date('Y-m-d H:i',$order->order_time)?></td>
	</tr>
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Order price')?></td>
		<td><?=$order->amount?></td>
	</tr>
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Order credits')?></td>
		<td><?=$order->amount_credits?></td>
	</tr>
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Order status')?></td>
		<td><?php	
			switch ($order->status) {
				case erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED: ?>
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Payed')?>.
					<?php break;
					
				case erLhcoreClassModelShopOrder::ORDER_STATUS_CONFIRMED:?>		
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Confirmed')?>			
					<?php			
					break;
			
				default:
					break;
			}	
			?>
	  </td>
	</tr>	
</table>
<br />
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Ordered items')?></h2>
<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Image name')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Version')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Image size')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Format')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Price')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Download count')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Downloads left')?></th>
</tr>
<?php foreach ($order->order_items as $item) : ?>
	<tr>
		<td><a href="<?=erLhcoreClassDesign::baseurl('gallery/image')?>/<?=$item->image->pid?>"><?=htmlspecialchars($item->image->name_user)?></a></td>
		<td><?=$item->image_variation->name?></td>
		<td><?=$item->image_variation->getSize($item->image)?></td>
		<td><?=$item->image_variation->getFormat($item->image)?></td>
		<td><?=$item->image_variation->credits?> (<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Credits')?>)</td>
		<td><?=$item->download_count?></td>
		<td>
		<?php if ($item->download_left > 0 && $order->status == erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED) : ?> 
		<a href="<?=erLhcoreClassDesign::baseurl('shop/download')?>/<?=$item->hash?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','Download image')?>"><?=$item->download_left?> <?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','downloads left')?></a>
		<? else : ?>
		<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderview','No downloads available')?>		
		<?php endif;?>
		</td>
	</tr>
<?php endforeach;?>
</table>