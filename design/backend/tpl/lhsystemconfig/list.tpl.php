<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Config list');?></h1>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<thead>
<tr>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Identifier');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Explain');?></th>
    <th width="1%">&nbsp;</th>
</tr>
</thead>
<? foreach (erLhcoreClassModelSystemConfig::getItems() as $item) : ?>
    <tr>
        <td><?=$item->identifier?></td>
        <td><?=$item->explain?></td>
        <td nowrap><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('systemconfig/edit')?>/<?=$item->identifier?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('systemconfig/list','Edit value');?></a></td>
    </tr>
<? endforeach; ?>
</table>