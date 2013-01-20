<h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Users');?></h1>

<form action="<?=erLhcoreClassDesign::baseurl('user/userlist')?>" method="get">
    <div class="row">
        <div class="columns one">
            <label class="inline"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','E-mail');?></label> 
        </div>
        <div class="columns two">
            <input type="text" value="<?=isset($filter['filter']['email']) ? $filter['filter']['email'] : ''?>" class="default-input" name="email" />
        </div> 
        <div class="columns one end">
            <input type="submit" name="filter" value="Search" class="small button" />
        </div>    
    </div>
</form>

<table class="lentele" cellpadding="0" cellspacing="0" width="100%">
<thead>
<tr>
    <th width="1%">ID</th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Username');?></th>
    <th><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','E-mail');?></th>
    <th width="1%"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Login');?></th>
    <th width="1%">&nbsp;</th>
    <th width="1%">&nbsp;</th>
</tr>
</thead>
<? foreach ($userlist as $user) : ?>
    <tr>
        <td><?=$user->id?></td>
        <td><?=$user->username?></td>
        <td><?=$user->email?></td>
        <td nowrap><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('user/loginas')?>/<?=$user->id?>">Login as</a></td>
        <td><a class="tiny button round" href="<?=erLhcoreClassDesign::baseurl('user/edit')?>/<?=$user->id?>">Edit</a></td>
        <td><a class="tiny alert button round" onclick="return confirm('<?=erTranslationClassLhTranslation::getInstance()->getTranslation('gallery/album_list_admin','Are you sure?');?>')" href="<?=erLhcoreClassDesign::baseurl('user/delete')?>/<?=$user->id?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','Delete');?></a></td>
    </tr>
<? endforeach; ?>
</table>
<?php if (isset($pages)) : ?>
    <?php include(erLhcoreClassDesign::designtpl('lhkernel/paginator.tpl.php')); ?>
<? endif;?>
<br />

<div>
<a class="small button" href="<?=erLhcoreClassDesign::baseurl('user/new')?>"><?=erTranslationClassLhTranslation::getInstance()->getTranslation('user/userlist','New user');?></a>
</div>
