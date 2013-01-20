<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Groups');?></h1>
<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<thead>
<tr>
    <th>ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Name');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
</thead>
<? foreach ($groups as $group) : ?>
    <tr>
        <td width="1%"><?=$group->id?></td>
        <td><?=$group->name?></td>
        <td nowrap><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('user/editgroup')?>/<?=$group->id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Edit group');?></a></td>
        <td nowrap><a class="tiny alert button round" onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('user/deletegroup')?>/<?=$group->id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','Delete group');?></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>
<a class="small button" href="<?=erLhcoreClassDesign::baseurl('user/newgroup')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/grouplist','New group');?></a>
