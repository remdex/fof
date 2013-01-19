<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Groups');?></h1></div>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Name');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
<? foreach ($groups as $group) : ?>
    <tr>
        <td width="1%"><?=$group->id?></td>
        <td><?=$group->name?></td>
        <td><a href="<?=erLhcoreClassDesign::baseurl('user/editgroup')?>/<?=$group->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/page_edit.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit group');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit group');?>" /></a></td>
        <td><a onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('user/deletegroup')?>/<?=$group->id?>"><img src="<?=erLhcoreClassDesign::design('images/icons/delete.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete group');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete group');?>" /></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>
<div>
<a href="<?=erLhcoreClassDesign::baseurl('user/newgroup')?>"><img src="<?=erLhcoreClassDesign::design('images/icons/add.png');?>" alt="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','New group');?>" title="<?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','New group');?>" /></a>
</div>
