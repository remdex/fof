<div class="header-list"><h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/roles','List of roles');?></h1></div>

<table class="lentele" cellpadding="0" cellspacing="0">
<thead>
<tr>
    <th width="1%">ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/roles','Title');?></th>
    <th width="5%">&nbsp;</th>
    <th width="5%">&nbsp;</th>
</tr>
</thead>
<? foreach (erLhcoreClassRole::getRoleList() as $departament) : ?>
    <tr>
        <td><?=$departament['id']?></td>
        <td><?=$departament['name']?></td>
        <td nowrap><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('permission/editrole')?>/<?=$departament['id']?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/roles','Edit role');?></a></td>
        <td nowrap><a class="tiny alert button round" onclick="return confirm('Are you sure?')" href="<?=erLhcoreClassDesign::baseurl('permission/deleterole')?>/<?=$departament['id']?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/roles','Delete role');?></a></td>
    </tr>
<? endforeach; ?>
</table>
<br />


<a class="button small" href="<?=erLhcoreClassDesign::baseurl('permission/newrole')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('permission/roles','New role');?></a>

