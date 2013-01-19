<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Config list');?></legend>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Identifier');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Explain');?></th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelSystemConfig::getItems() as $item) : ?>
    <tr>
        <td><?=$item->identifier?></td>
        <td><?=$item->explain?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('systemconfig/edit')?>/<?=$item->identifier?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Edit value');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Edit value');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />

</fieldset>