<div class="header-list">
<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','My credits orders')?></h1>
</div>

<br />
<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
	<th>ID</th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Date')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order price')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order credits')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/myorders','Order status')?></th>
</tr>
<?php foreach ($orders as $order) : ?>
<tr>
	<td><?=$order->id?></td>
	<td><?=date('Y-m-d H:i',$order->date)?></td>
	<td><?=$order->amount?></td>
	<td><?=$order->credits?></td>
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
</tr>
<?php endforeach; ?>
</table>

