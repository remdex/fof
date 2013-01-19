<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','Baned IP for rating');?></h1></div>



<?php if ( !empty($items) ) : ?>	
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','IP');?></th>
    <th width="1%">&nbsp;</th>  
</tr>
<? foreach ($items as $user) : ?>
    <tr>
        <td width="99%"><?=$user->ip?></td>  
        <td><a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('gallery/ratebanlist')?>/(delete)/<?=$user->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','Delete ip');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','Delete ip');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />
<?php else : ?>
No banned IP's
<?php endif;?>


<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>
<br />
<br />
<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','New IP BAN')?></h1></div>
<form action="" method="post">
<div class="in-blk">
	<label><?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','IP');?> *</label>
	<input class="default-input" type="text" name="IP" value="" />
</div>
<input type="submit" class="default-button" name="AddIP" value="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/ratebanlist','Add')?>"/>
</form>