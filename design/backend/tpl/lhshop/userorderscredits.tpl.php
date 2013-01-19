<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','User credits orders list');?></legend>
<div class="float-break">
<?php if ($pages->items_total > 0) :?>

<?php if (isset($pages)) : ?> 
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>


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
<? foreach (erLhcoreClassModelShopUserCreditOrder::getList(array('filterin' => array('status' => array(1,2)),'filter' => array('user_id' => $user_id),'offset' => $pages->low, 'limit' => $pages->items_per_page)) as $order) : ?>
    <tr>
	<td><?=$order->id?></td>
	<td><?=date('Y-m-d H:i',$order->date)?></td>
	<td><?=$order->user->email?></td>
	<td><?=$order->amount?></td>
	<td><?=$order->credits?></td>
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
	<td><a href="<?=erLhcoreClassDesign::baseurl('shop/deleteordercredit')?>/<?=$order->id?>/<?=$order->user_id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Delete order');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Delete order');?>" /></a></td>
</tr>
<? endforeach; ?>
</table>

<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>

<?php else : ?>
<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/orderslist','No orders...');?>
<?endif;?>
<br />


</div>

</fieldset>