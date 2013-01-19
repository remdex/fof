Order mail template

<?php foreach ($order->order_items as $item) : ?>
http://<?=$_SERVER['HTTP_HOST']?><?=erLhcoreClassDesign::baseurl('/shop/download')?>/<?=$item->hash?><?=$item->image->name_user?> (<?=$item->image_variation->name?>)
<?php endforeach;?>
