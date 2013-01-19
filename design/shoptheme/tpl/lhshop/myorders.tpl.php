<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','My orders')?></h1>
</div>

<br />
<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
	<th>ID</th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Date')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order price')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order credits')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order status')?></th>
	<th>&nbsp;</th>
</tr>
<?php foreach ($orders as $order) : ?>
<tr>
	<td><?=$order->id?></td>
	<td><?=date('Y-m-d H:i',$order->order_time)?></td>
	<td><?=$order->amount?></td>
	<td><?=$order->amount_credits?></td>
	<td><?php	
	switch ($order->status) {
		case erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED: ?>
			<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Payed')?>.
			<?php break;
			
		case erLhcoreClassModelShopOrder::ORDER_STATUS_CONFIRMED:?>		
			<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Confirmed')?>			
			<?php			
			break;
	
		default:
			break;
	}	
	?>
	</td>
	<td><a href="<?=erLhcoreClassDesign::baseurl('shop/orderview')?>/<?=$order->id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order view')?></a></td>

</tr>
<?php endforeach; ?>
</table>

