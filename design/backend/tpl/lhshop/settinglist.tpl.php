<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Shop config list');?></legend>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Identifier');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Explain');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Value');?></th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelShopBaseSetting::getList() as $item) : ?>
    <tr>
        <td><?=$item->identifier?></td>
        <td><?=$item->explain?></td>
        <td><?=$item->value?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('shop/settingedit')?>/<?=$item->identifier?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Edit value');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/settinglist','Edit value');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />

</fieldset>



