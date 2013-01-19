<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Order view');?></legend>

<table>
	<tr>
		<td>ID</td>
		<td><?=$order->id?></td>
	</tr>	
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Order time');?></td>
		<td><?=date('Y-m-d H:i',$order->order_time)?></td>
	</tr>
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Order price');?></td>
		<td><?=$order->amount?></td>
	</tr>
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Order credits');?></td>
		<td><?=$order->amount_credits?></td>
	</tr>
	<tr>
		<td><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Order status');?></td>
		<td>		
		<?php	
			switch ($order->status) {
				case erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED: ?>
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Payed.');?>
					<?php break;
					
				case erLhcoreClassModelShopOrder::ORDER_STATUS_CONFIRMED:?>		
					<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Confirmed');?>			
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
<h2><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Ordered items');?></h2>
<table class="lentele" cellpadding="0" cellspacing="0">
<tr>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Image name')?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Version');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Image size');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Format');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Price');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Download count');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Downloads left');?></th>
	<th>&nbsp;</th>
</tr>
<?php foreach ($order->order_items as $item) : ?>
	<tr>
	    <td><a href="<?=$item->image->url_path?>"><?=htmlspecialchars($item->image->name_user)?></a></td>
		<td><?=$item->image_variation->name?></td>
		<td><?=$item->image_variation->getSize($item->image)?></td>
		<td><?=$item->image_variation->getFormat($item->image)?></td>
		<td><?=$item->image_variation->credits?> (<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderedit','Credits');?>)</td>
		<td><?=$item->download_count?></td>
		<td><?=$item->download_left?></td>
		<td><a href="<?=erLhcoreClassDesign::baseurl('shop/deleteorderitem')?>/<?=$item->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Delete order item');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Delete order item');?>" /></a></td>
	</tr>
<?php endforeach;?>
</table>

</fieldset>