Order mail template

<ul>
<?php foreach ($order->order_items as $item) : ?>
	<li><a href="http://<?=$_SERVER['HTTP_HOST']?><?=erLhcoreClassDesign::baseurl('/shop/download')?>/<?=$item->hash?>"><?=$item->image->name_user?> (<?=$item->image_variation->name?>)</a></li>
<?php endforeach;?>
</ul>