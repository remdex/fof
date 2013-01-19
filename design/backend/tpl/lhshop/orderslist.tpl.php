<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Orders list');?></legend>
<?php if ($pages->items_total > 0) :?>

<?php if (isset($pages)) : ?> 
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<div class="float-break">

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','ID');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Date');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','E-mail');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Order price');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Order credits');?></th>
	<th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Order status');?></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelShopOrder::getList(array('filterin' => array('status' => array(1,2)),'offset' => $pages->low, 'limit' => $pages->items_per_page)) as $order) : ?>
    <tr>
	<td><?=$order->id?></td>
	<td><?=date('Y-m-d H:i',$order->order_time)?></td>
	<td><a href="<?=erLhcoreClassDesign::baseurl('shop/userorders')?>/<?=$order->user_id?>/"><?=$order->email?></a></td>
	<td><?=$order->amount?></td>
	<td><?=$order->amount_credits?></td>
	<td><?php	
	switch ($order->status) {
		case erLhcoreClassModelShopOrder::ORDER_STATUS_PAYED: ?>
			<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Payed.');?>
			<?php break;
			
		case erLhcoreClassModelShopOrder::ORDER_STATUS_CONFIRMED:?>		
			<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Confirmed');?>		
			<?php			
			break;
	
		default:
			break;
	}	
	?>
	</td>
	<td><a href="<?=erLhcoreClassDesign::baseurl('shop/orderedit')?>/<?=$order->id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','Order view');?></a></td>
	<td><a href="<?=erLhcoreClassDesign::baseurl('shop/deleteorder')?>/<?=$order->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Delete order');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Delete order');?>" /></a></td>

</tr>
<? endforeach; ?>
</table>   
<br />

<?php if (isset($pages)) : ?>
<?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

</div>
<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','No orders...');?>
<?endif;?>





</fieldset>