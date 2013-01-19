<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Payment options');?></legend>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Identifier');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Name');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Active');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Author');?></th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassShopPaymentHandler::getHandlers() as $item) : ?>
    <tr>
        <td nowrap><?=$item->identifier?></td>
        <td><?=$item->name?></td>
        <td><?=$item->active == true ? 'Yes' : 'No'?></td>
        <td><?=$item->author?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('shop/paymentoptionedit')?>/<?=$item->identifier?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Edit');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/listpaymentoptions','Edit');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />
</fieldset>