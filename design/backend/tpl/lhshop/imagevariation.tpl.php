<fieldset><legend><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Variations list');?></legend>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Name');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Width');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Height');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Credits');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Applies to original');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach (erLhcoreClassModelShopImageVariation::getImageVariation() as $item) : ?>
    <tr>
        <td nowrap><?=$item->name?></td>
        <td><?=$item->width?></td>
        <td><?=$item->height?></td>
        <td><?=$item->credits?></td>
        <td><?=$item->type == erLhcoreClassModelShopImageVariation::CUSTOM_VARIATION ? erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','No') : erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Yes') ?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('shop/imagevariationedit')?>/<?=$item->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Edit');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Edit');?>" /></a></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('shop/imagevariationdelete')?>/<?=$item->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Delete');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','Delete');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />
<a href="<?=erLhcoreClassDesign::baseurl('shop/imagevariationnew')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('shop/imagevariation','New variation');?> &raquo;</a>
</fieldset>